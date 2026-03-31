<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CustomCreditController extends Controller
{
    public function show(Request $request)
    {
        $user   = Auth::user();
        $amount = max(1, min(1000, (int) $request->get('amount', 10)));
        $price  = $amount * 490; // kuruş cinsinden (4.90 TL * 100)

        return view('dashboard.custom-credit', compact('user', 'amount', 'price'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1|max:1000',
        ]);

        $user    = Auth::user();
        $amount  = (int) $request->amount;
        $price   = $amount * 490;
        $orderId = 'CUSTOM-' . strtoupper(Str::random(10));

        $transaction = Transaction::create([
            'user_id'       => $user->id,
            'package_id'    => null,
            'order_id'      => $orderId,
            'amount'        => $price,
            'currency'      => 'TRY',
            'credit_amount' => $amount,
            'status'        => 'pending',
            'type'          => 'purchase',
            'note'          => 'Özel kredi satın alma: ' . $amount . ' kredi',
        ]);

        // Polar checkout
        $mode    = env('POLAR_MODE', 'sandbox');
        $baseUrl = $mode === 'sandbox'
            ? 'https://sandbox-api.polar.sh'
            : 'https://api.polar.sh';

        $successUrl = route('payment.success') . '?order_id=' . $orderId . '&checkout_id={CHECKOUT_ID}';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('POLAR_ACCESS_TOKEN'),
            'Content-Type'  => 'application/json',
        ])->post($baseUrl . '/v1/checkouts/custom/', [
            'product_price_id' => env('POLAR_PRICE_ID'),
            'success_url'      => $successUrl,
            'customer_email'   => $user->email,
            'customer_name'    => $user->name,
            'metadata'         => [
                'order_id'      => $orderId,
                'user_id'       => $user->id,
                'credit_amount' => $amount,
            ],
        ]);

        if (!$response->successful()) {
            return back()->with('error', 'Ödeme başlatılamadı: ' . $response->body());
        }

        $data = $response->json();

        $transaction->update([
            'pos_transaction_id' => $data['id'] ?? null,
            'pos_payment_url'    => $data['url'] ?? null,
        ]);

        return redirect($data['url']);
    }
}