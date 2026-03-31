<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WiroService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected bool   $mockMode;

    public function __construct()
    {
        $this->apiKey   = env('WIRO_API_KEY', '');
        $this->apiUrl   = env('WIRO_API_URL', 'https://api.wiro.ai/v1');
        $this->mockMode = env('WIRO_MOCK', true);
    }

    public function startJob(string $personImagePath, string $garmentImagePath, string $prompt = ''): array
    {
        if ($this->mockMode) {
            return $this->mockStartJob();
        }

        try {
            $personFullPath  = Storage::disk('public')->path($personImagePath);
            $garmentFullPath = Storage::disk('public')->path($garmentImagePath);

            if (!file_exists($personFullPath) || !file_exists($garmentFullPath)) {
                return ['success' => false, 'error' => 'Fotoğraf dosyası bulunamadı.'];
            }

            $multipart = [];
            $multipart[] = [
                'name'     => 'inputImage',
                'contents' => fopen($personFullPath, 'r'),
                'filename' => 'person.jpg',
            ];
            $multipart[] = [
                'name'     => 'inputImage',
                'contents' => fopen($garmentFullPath, 'r'),
                'filename' => 'garment.jpg',
            ];
            $multipart[] = [
                'name'     => 'prompt',
                'contents' => $prompt ?: 'Try on the clothing item on the person. Keep the person\'s pose and background.',
            ];
            $multipart[] = ['name' => 'temperature',   'contents' => '1'];
            $multipart[] = ['name' => 'aspectRatio',   'contents' => 'match_input_image'];
            $multipart[] = ['name' => 'safetySetting', 'contents' => 'OFF'];

            $client   = new Client();
            $response = $client->request('POST', $this->apiUrl . '/Run/google/nano-banana', [
                'multipart' => $multipart,
                'headers'   => [
                    'x-api-key' => $this->apiKey,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!($data['result'] ?? false)) {
                $errorMsg = $data['errors'][0]['message'] ?? 'Bilinmeyen hata';
                Log::error('Wiro startJob result false', ['data' => $data]);
                return ['success' => false, 'error' => $errorMsg];
            }

            return [
                'success'             => true,
                'task_id'             => $data['taskid'],
                'socket_access_token' => $data['socketaccesstoken'],
                'status'              => 'processing',
            ];

        } catch (\Exception $e) {
            Log::error('Wiro startJob exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getJobStatus(string $taskId, string $socketToken = ''): array
    {
        if ($this->mockMode) {
            return $this->mockGetJobStatus($taskId);
        }

        try {
            $payload = $socketToken
                ? ['tasktoken' => $socketToken]
                : ['taskid'    => $taskId];

            $ch = curl_init($this->apiUrl . '/Task/Detail');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'x-api-key: ' . $this->apiKey,
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            $raw = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($raw, true);

            if (!($data['result'] ?? false) || empty($data['tasklist'])) {
                return ['success' => false, 'error' => 'Task bulunamadı'];
            }

            $task   = $data['tasklist'][0];
            $status = $task['status'] ?? 'task_queue';
            $pexit  = $task['pexit'] ?? null;

            if ($status === 'task_postprocess_end') {
                if ($pexit === '0') {
                    $resultUrl = $task['outputs'][0]['url'] ?? null;
                    return [
                        'success'    => true,
                        'status'     => 'completed',
                        'progress'   => 100,
                        'result_url' => $resultUrl,
                        'total_cost' => $task['totalcost'] ?? '0',
                        'error'      => null,
                    ];
                } else {
                    return [
                        'success'  => true,
                        'status'   => 'failed',
                        'progress' => 0,
                        'error'    => 'İşlem başarısız oldu (pexit: ' . $pexit . ')',
                    ];
                }
            }

            return [
                'success'    => true,
                'status'     => 'processing',
                'progress'   => $this->calculateProgress($status),
                'result_url' => null,
                'error'      => null,
            ];

        } catch (\Exception $e) {
            Log::error('Wiro getJobStatus exception', ['task_id' => $taskId, 'message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function cancelJob(string $taskId): bool
    {
        if ($this->mockMode) return true;

        try {
            $ch = curl_init($this->apiUrl . '/Task/Cancel');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['taskid' => $taskId]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'x-api-key: ' . $this->apiKey,
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $raw = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($raw, true);
            return $data['result'] ?? false;
        } catch (\Exception $e) {
            Log::error('Wiro cancelJob exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function killJob(string $taskId): bool
    {
        if ($this->mockMode) return true;

        try {
            $ch = curl_init($this->apiUrl . '/Task/Kill');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['taskid' => $taskId]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'x-api-key: ' . $this->apiKey,
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $raw = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($raw, true);
            return $data['result'] ?? false;
        } catch (\Exception $e) {
            Log::error('Wiro killJob exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    private function calculateProgress(string $status): int
    {
        return match($status) {
            'task_queue'             => 5,
            'task_accept'            => 15,
            'task_preprocess_start'  => 25,
            'task_preprocess_end'    => 35,
            'task_assign'            => 45,
            'task_start'             => 55,
            'task_output'            => 70,
            'task_output_full'       => 80,
            'task_end'               => 85,
            'task_postprocess_start' => 90,
            'task_postprocess_end'   => 100,
            default                  => 10,
        };
    }

    private function mockStartJob(): array
    {
        $mockId = 'mock_' . uniqid();
        Log::info('Wiro MOCK startJob', ['task_id' => $mockId]);

        return [
            'success'             => true,
            'task_id'             => $mockId,
            'socket_access_token' => 'mock_token_' . uniqid(),
            'status'              => 'processing',
        ];
    }

    private function mockGetJobStatus(string $taskId): array
    {
        $cacheKey = 'mock_job_' . $taskId;
        $count    = cache($cacheKey, 0) + 1;
        cache([$cacheKey => $count], now()->addMinutes(10));

        $progress = min(100, $count * 20);
        $isDone   = $progress >= 100;

        return [
            'success'    => true,
            'status'     => $isDone ? 'completed' : 'processing',
            'progress'   => $progress,
            'result_url' => $isDone
                ? 'https://placehold.co/512x768/1a1a2e/FFFFFF.png?text=TryOn+Result'
                : null,
            'total_cost' => $isDone ? '0.09' : '0',
            'error'      => null,
        ];
    }
}
