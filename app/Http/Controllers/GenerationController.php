<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Services\WiroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerationController extends Controller
{
    protected WiroService $wiro;

    public function __construct(WiroService $wiro)
    {
        $this->wiro = $wiro;
    }

    /**
     * Yeni deneme başlat
     */
    public function store(Request $request)
    {
        $request->validate([
            'person_image'  => 'required|image|max:10240|mimes:jpg,jpeg,png,webp',
            'garment_image' => 'required|image|max:10240|mimes:jpg,jpeg,png,webp',
            'prompt'        => 'nullable|string|max:500',
        ]);

       $user = Auth::user();

if (!$user) {
    return response()->json([
        'success' => false,
        'error'   => 'unauthenticated',
        'message' => 'Giriş yapmanız gerekiyor.',
    ], 401);
}


        // Kredi kontrolü
        if ($user->credit_balance < 1) {
            return response()->json([
                'success' => false,
                'error'   => 'insufficient_credits',
                'message' => __('insufficient_credits'),
                'redirect'=> route('dashboard') . '#tab-credits',
            ], 422);
        }

        try {
            // Benzersiz bir klasör ID'si üret
            $runId = uniqid();
            $folder = 'generations/' . $user->id . '/' . $runId;

            // Önce fotoğrafları storage'a kaydet (public disk)
            $personPath  = $request->file('person_image')->storeAs(
                $folder, 'person.' . $request->file('person_image')->extension(),
                'public'
            );
            $garmentPath = $request->file('garment_image')->storeAs(
                $folder, 'garment.' . $request->file('garment_image')->extension(),
                'public'
            );

            if (!$personPath || !$garmentPath) {
                return response()->json([
                    'success' => false,
                    'error'   => 'upload_failed',
                    'message' => 'Fotoğraf yüklenemedi. Lütfen tekrar deneyin.',
                ], 500);
            }

            // Generation kaydını DB'ye kaydet (Tüm gerekli alanlarla birlikte)
            $generation = Generation::create([
                'user_id'            => $user->id,
                'source'             => 'dashboard',
                'person_image_path'  => $personPath,
                'garment_image_path' => $garmentPath,
                'status'             => 'processing',
                'progress'           => 0,
                'started_at'         => now(),
                'expires_at'         => now()->addDays(30),
            ]);

            // Wiro API'ye gönder
            $result = $this->wiro->startJob(
                $personPath,
                $garmentPath,
                $request->get('prompt', '')
            );

            if (!$result['success']) {
                $generation->update([
                    'status'        => 'failed',
                    'error_message' => $result['error'],
                    'completed_at'  => now(),
                ]);

                return response()->json([
                    'success' => false,
                    'error'   => 'wiro_error',
                    'message' => $result['error'],
                ], 500);
            }

            // Wiro job_id kaydet
            $generation->update([
                'wiro_job_id' => $result['task_id'],
            ]);

            // 1 kredi düş
            $user->deductCredits(1, 'usage', 'AI deneme kabini kullanıldı');

            return response()->json([
                'success'       => true,
                'generation_id' => $generation->id,
                'message'       => 'İşlem başladı',
            ]);

        } catch (\Exception $e) {
            Log::error('GenerationController store exception', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            // Eğer generation oluşturulduysa failed yap
            if (isset($generation)) {
                $generation->update([
                    'status'        => 'failed',
                    'error_message' => $e->getMessage(),
                    'completed_at'  => now(),
                ]);
            }

            return response()->json([
                'success' => false,
                'error'   => 'server_error',
                'message' => 'Sunucu hatası oluştu.',
            ], 500);
        }
    }

    /**
     * Job durumunu sorgula (frontend polling)
     */
    public function status(Request $request, int $id)
    {
        $user       = Auth::user();
        $generation = Generation::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Zaten tamamlandıysa direkt döndür
        if ($generation->status === 'completed') {
            return response()->json([
                'success'    => true,
                'status'     => 'completed',
                'progress'   => 100,
                'result_url' => $generation->result_image_path ? Storage::disk('public')->url($generation->result_image_path) : null,
            ]);
        }

        // Zaten başarısızsa direkt döndür
        if ($generation->status === 'failed') {
            return response()->json([
                'success' => true,
                'status'  => 'failed',
                'progress'=> 0,
                'error'   => $generation->error_message,
            ]);
        }

        // 60 saniye timeout kontrolü
        if ($generation->started_at && now()->diffInSeconds($generation->started_at) > 60) {
            $generation->update([
                'status'        => 'failed',
                'progress'      => 0,
                'error_message' => 'Zaman aşımı (60 saniye)',
                'completed_at'  => now(),
            ]);

            // Krediyi iade et
            $user->addCredits(1, 'refund', 'AI deneme zaman aşımı - kredi iadesi');

            return response()->json([
                'success' => true,
                'status'  => 'failed',
                'progress'=> 0,
                'error'   => 'timeout',
                'message' => 'İşlem zaman aşımına uğradı. Krediniz iade edildi.',
            ]);
        }

        // Wiro'dan durum sorgula
        $result = $this->wiro->getJobStatus(
            $generation->wiro_job_id ?? '',
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'status'  => 'processing',
                'progress'=> $generation->progress,
                'error'   => $result['error'],
            ]);
        }

        // Progress güncelle
        $generation->update(['progress' => $result['progress']]);

        // Tamamlandıysa
        if ($result['status'] === 'completed' && $result['result_url']) {
            // Sonuç görselini aynı klasöre indir
            $folder = str_replace('\\', '/', dirname($generation->person_image_path));
            $resultPath = $this->downloadResult(
                $result['result_url'],
                $folder . '/result.png'
            );

            // Referans fotoğraflarını sil (Kullanıcı isteği: gizlilik ve alan tasarrufu)
            Storage::disk('public')->delete([
                $generation->person_image_path,
                $generation->garment_image_path
            ]);

            $generation->update([
                'status'            => 'completed',
                'progress'          => 100,
                'result_image_path' => $resultPath,
                'result_image_url'  => $result['result_url'],
                'completed_at'      => now(),
            ]);

            return response()->json([
                'success'    => true,
                'status'     => 'completed',
                'progress'   => 100,
                'result_url' => $resultPath
                    ? Storage::disk('public')->url($resultPath)
                    : $result['result_url'],
            ]);
        }

        // Wiro'da başarısız olduysa
        if ($result['status'] === 'failed') {
            $generation->update([
                'status'        => 'failed',
                'progress'      => 0,
                'error_message' => $result['error'] ?? 'Wiro işlemi başarısız',
                'completed_at'  => now(),
            ]);

            // Krediyi iade et
            $user->addCredits(1, 'refund', 'AI deneme başarısız - kredi iadesi');

            return response()->json([
                'success' => true,
                'status'  => 'failed',
                'progress'=> 0,
                'error'   => 'wiro_failed',
                'message' => 'İşlem başarısız oldu. Krediniz iade edildi.',
            ]);
        }

        // Hala işleniyor
        return response()->json([
            'success'  => true,
            'status'   => 'processing',
            'progress' => $result['progress'],
        ]);
    }

    /**
     * Sonuç görselini indir ve storage'a kaydet
     */
    private function downloadResult(string $url, string $savePath): ?string
    {
        try {
            $contents = file_get_contents($url);
            if ($contents === false) {
                Log::error('Result download failed', ['url' => $url]);
                return null;
            }
            Storage::disk('public')->put($savePath, $contents);
            return $savePath;
        } catch (\Exception $e) {
            Log::error('downloadResult exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Stüdyo geçmişinden kaydı ve fotoğrafı sil
     */
    public function destroy(Request $request, int $id)
    {
        $user = Auth::user();
        $generation = Generation::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Eğer bitmemişse silinmesine izin verme (veya kuyruktan silme eklenebilir)
        if (!in_array($generation->status, ['completed', 'failed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Devam eden işlemler silinemez.'
            ], 422);
        }

        // Fiziksel dosyaları sil
        $pathsToDelete = array_filter([
            $generation->person_image_path,
            $generation->garment_image_path,
            $generation->result_image_path
        ]);
        
        if (!empty($pathsToDelete)) {
            Storage::disk('public')->delete($pathsToDelete);
        }

        // Kaydı sil
        $generation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kayıt başarıyla silindi.'
        ]);
    }
}