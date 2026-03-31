<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // ── Yardımcı: Polar API base URL ──────────────────────────────────────────
    private function polarBaseUrl(): string
    {
        $mode = config('services.polar.mode', env('POLAR_MODE', 'sandbox'));

        return $mode === 'sandbox'
            ? 'https://sandbox-api.polar.sh'
            : 'https://api.polar.sh';
    }

    // ── Yardımcı: Polar Authorization header ─────────────────────────────────
    private function polarHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . config('services.polar.token', env('POLAR_ACCESS_TOKEN')),
            'Content-Type'  => 'application/json',
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // show — Ödeme sayfasını göster
    // ─────────────────────────────────────────────────────────────────────────
    public function show(string $slug)
    {
        $package = Package::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $user    = Auth::user();

        return view('payment.show', compact('package', 'user'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // process — Ödeme başlat, Polar checkout oluştur
    // ─────────────────────────────────────────────────────────────────────────
    public function process(Request $request, string $slug)
    {
        $package = Package::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $user    = Auth::user();

        $orderId = 'ORD-' . strtoupper(Str::random(12));

        $transaction = Transaction::create([
            'user_id'       => $user->id,
            'package_id'    => $package->id,
            'order_id'      => $orderId,
            'amount'        => $package->price,
            'currency'      => 'TRY',
            'credit_amount' => $package->credit_amount,
            'status'        => 'pending',
            'type'          => 'purchase',
        ]);

        $successUrl = route('payment.success') . '?order_id=' . $orderId . '&checkout_id={CHECKOUT_ID}';

        $response = Http::withHeaders($this->polarHeaders())
            ->post($this->polarBaseUrl() . '/v1/checkouts/custom/', [
                'product_price_id' => config('services.polar.price_id', env('POLAR_PRICE_ID')),
                'success_url'      => $successUrl,
                'customer_email'   => $user->email,
                'customer_name'    => $user->name,
                'metadata'         => [
                    'order_id'   => $orderId,
                    'user_id'    => $user->id,
                    'package_id' => $package->id,
                ],
            ]);

        if (! $response->successful()) {
            $transaction->update(['status' => 'failed']);
            return back()->with('error', 'Ödeme başlatılamadı: ' . $response->body());
        }

        $data = $response->json();

        $transaction->update([
            'pos_transaction_id' => $data['id'] ?? null,
            'pos_payment_url'    => $data['url'] ?? null,
        ]);

        return redirect($data['url']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // success — Polar'ın yönlendirdiği başarı sayfası
    // ─────────────────────────────────────────────────────────────────────────
    public function success(Request $request)
    {
        $orderId    = $request->get('order_id');
        $checkoutId = $request->get('checkout_id');

        // Sadece bu kullanıcıya ait işlemi al
        $transaction = Transaction::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $transaction) {
            return redirect()->route('dashboard');
        }

        // Zaten ödendi ise tekrar işlem yapma
        if ($transaction->status === 'paid') {
            return view('payment.success', compact('transaction'));
        }

        $response = Http::withHeaders($this->polarHeaders())
            ->get($this->polarBaseUrl() . '/v1/checkouts/custom/' . $checkoutId);

        if ($response->successful()) {
            $checkout = $response->json();
            $status   = $checkout['status'] ?? '';

            if (in_array($status, ['succeeded', 'confirmed', 'complete'])) {

                // Sadece bir kez kredi ekle — DB transaction + row lock
                \DB::transaction(function () use ($transaction, $checkout) {
                    $fresh = Transaction::where('id', $transaction->id)
                        ->where('status', '!=', 'paid')
                        ->lockForUpdate()
                        ->first();

                    if ($fresh) {
                        $fresh->update([
                            'status'      => 'paid',
                            'paid_at'     => now(),
                            'pos_payload' => $checkout,
                        ]);

                        $fresh->user->addCredits(
                            $fresh->credit_amount,
                            'purchase',
                            ($fresh->package?->name ?? 'Paket') . ' satın alındı'
                        );
                    }
                });

                $transaction->refresh();
            }
        }

        return view('payment.success', compact('transaction'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // failed — Ödeme başarısız sayfası
    // ─────────────────────────────────────────────────────────────────────────
    public function failed(Request $request)
    {
        $orderId = $request->get('order_id');

        // 🔒 Sadece bu kullanıcıya ait işlemi güncelle
        $transaction = Transaction::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        $transaction?->update(['status' => 'failed']);

        return view('payment.failed', compact('transaction'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // webhook — Polar'dan gelen event (CSRF dışında tutulmalı)
    // ─────────────────────────────────────────────────────────────────────────
    public function webhook(Request $request)
    {
        $secret    = config('services.polar.webhook_secret', env('POLAR_WEBHOOK_SECRET'));
        $signature = $request->header('webhook-signature');
        $payload   = $request->getContent();

        if (! $this->verifyWebhookSignature($payload, $signature, $secret)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data  = $request->json()->all();
        $type  = $data['type'] ?? null;
        $event = $data['data'] ?? [];

        if ($type === 'checkout.updated' && ($event['status'] ?? '') === 'succeeded') {
            $metadata = $event['metadata'] ?? [];
            $orderId  = $metadata['order_id'] ?? null;

            if ($orderId) {
                \DB::transaction(function () use ($orderId, $event) {
                    $transaction = Transaction::where('order_id', $orderId)
                        ->where('status', '!=', 'paid')
                        ->lockForUpdate()
                        ->first();

                    if ($transaction) {
                        $transaction->update([
                            'status'      => 'paid',
                            'paid_at'     => now(),
                            'pos_payload' => $event,
                        ]);

                        $transaction->user->addCredits(
                            $transaction->credit_amount,
                            'purchase',
                            ($transaction->package?->name ?? 'Paket') . ' satın alındı'
                        );
                    }
                });
            }
        }

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // verifyWebhookSignature — Polar HMAC-SHA256 imza doğrulama
    // ─────────────────────────────────────────────────────────────────────────
    private function verifyWebhookSignature(string $payload, ?string $signature, string $secret): bool
    {
        if (! $signature || ! $secret) {
            return false;
        }

        // Polar webhook-signature formatı: "v1,<imza>" veya "v1,<timestamp>,<imza>"
        $parts = explode(',', $signature);

        if (count($parts) < 2) {
            return false;
        }

        $expectedSig = hash_hmac('sha256', $payload, $secret);

        foreach ($parts as $part) {
            if (hash_equals($expectedSig, $part)) {
                return true;
            }
        }

        return false;
    }
}