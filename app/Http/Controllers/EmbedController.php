<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Generation;
use App\Services\WiroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmbedController extends Controller
{
    protected WiroService $wiro;

    public function __construct(WiroService $wiro)
    {
        $this->wiro = $wiro;
    }

    public function script(Request $request)
    {
        $apiKey = $request->query('key', '');
        $domain = Domain::where('api_key', $apiKey)
            ->where('status', 'active')
            ->first();

        if (!$domain) {
            $js = "console.error('[WiroEmbed] Geçersiz veya onaylanmamış API anahtarı.');";
            return response($js, 403)->header('Content-Type', 'application/javascript');
        }

        $baseUrl  = rtrim(config('app.url'), '/');
        $domainId = $domain->id;

        $js = <<<JS
(function() {
  'use strict';
  if (window.__WiroEmbed) return;
  window.__WiroEmbed = true;

  var BASE = '{$baseUrl}';
  var DOMAIN_ID = {$domainId};

  function findBestImage() {
    var imgs = Array.from(document.querySelectorAll('img'));
    var scored = imgs.map(function(img) {
      var score = 0;
      var w = img.naturalWidth || img.width || 0;
      var h = img.naturalHeight || img.height || 0;
      if (w > 200 && h > 200) score += 10;
      if (w > 400 && h > 400) score += 10;
      var src = (img.src || '').toLowerCase();
      var alt = (img.alt || '').toLowerCase();
      var cls = (img.className || '').toLowerCase();
      var keywords = ['product','kiyafet','clothing','outfit','fashion','model','wear','giyim','urun'];
      keywords.forEach(function(k){ if(src.includes(k)||alt.includes(k)||cls.includes(k)) score += 5; });
      if (img.closest('[class*="product"]') || img.closest('[class*="item"]')) score += 8;
      return { img: img, score: score };
    });
    scored.sort(function(a,b){ return b.score - a.score; });
    return scored.length ? scored[0].img : null;
  }

  function createStyles() {
    var style = document.createElement('style');
    style.textContent = [
      '.wiro-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:linear-gradient(135deg,#3B82F6,#1D4ED8);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 4px 15px rgba(59,130,246,0.4);transition:all .2s;font-family:inherit;letter-spacing:.01em}',
      '.wiro-btn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(59,130,246,0.5)}',
      '.wiro-btn:disabled{opacity:.7;cursor:not-allowed;transform:none}',
      '.wiro-btn svg{flex-shrink:0}',
      '.wiro-loader{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:wiro-spin .7s linear infinite}',
      '@keyframes wiro-spin{to{transform:rotate(360deg)}}'
    ].join('');
    document.head.appendChild(style);
  }

  function injectButtons() {
    var targets = document.querySelectorAll('[data-wiro-target], [data-wiro-garment]');
    if (!targets.length) {
      var bestImg = findBestImage();
      if (bestImg) {
        var wrapper = bestImg.parentElement;
        var btn = createButton(bestImg.src);
        wrapper.style.position = 'relative';
        wrapper.appendChild(btn);
      }
    } else {
      targets.forEach(function(el) {
        var garmentUrl = el.dataset.wiroGarment || (el.tagName === 'IMG' ? el.src : '');
        var btn = createButton(garmentUrl);
        if (el.dataset.wiroTarget) {
          var t = document.querySelector(el.dataset.wiroTarget);
          if (t) t.appendChild(btn);
        } else {
          el.parentElement.appendChild(btn);
        }
      });
    }
  }

  function createButton(garmentUrl) {
    var btn = document.createElement('button');
    btn.className = 'wiro-btn';
    btn.dataset.garmentUrl = garmentUrl || '';
    btn.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M12 2C9.5 2 8 4 8 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-3s-1.5-2-4-2z"/><circle cx="12" cy="13" r="3" stroke="currentColor" stroke-width="2"/></svg>Dene';
    btn.addEventListener('click', function() {
      handleClick(btn, garmentUrl);
    });
    return btn;
  }

  function handleClick(btn, garmentUrl) {
    btn.disabled = true;
    btn.innerHTML = '<span class="wiro-loader"></span>Aranıyor...';

    var finalGarment = garmentUrl;
    if (!finalGarment) {
      var img = findBestImage();
      finalGarment = img ? img.src : '';
    }

    if (!finalGarment) {
      btn.disabled = false;
      btn.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M12 2C9.5 2 8 4 8 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-3s-1.5-2-4-2z"/><circle cx="12" cy="13" r="3" stroke="currentColor" stroke-width="2"/></svg>Dene';
      alert('Kıyafet görseli bulunamadı. Lütfen sayfayı yenileyip tekrar deneyin.');
      return;
    }

    var params = new URLSearchParams({
      domain_id: DOMAIN_ID,
      garment_url: finalGarment,
      ref: window.location.href
    });
    window.open(BASE + '/dene?' + params.toString(), '_blank', 'width=520,height=700,scrollbars=yes');

    setTimeout(function() {
      btn.disabled = false;
      btn.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M12 2C9.5 2 8 4 8 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-3s-1.5-2-4-2z"/><circle cx="12" cy="13" r="3" stroke="currentColor" stroke-width="2"/></svg>Dene';
    }, 1500);
  }

  createStyles();
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', injectButtons);
  } else {
    injectButtons();
  }
})();
JS;

        return response($js)->header('Content-Type', 'application/javascript');
    }

    public function tryPage(Request $request)
    {
        $domainId   = $request->query('domain_id');
        $domainName = $request->query('domain');
        $garmentUrl = $request->query('garment_url', '');
        $ref        = $request->query('ref', '');

        $query = Domain::where('status', 'active');

        if ($domainId) {
            $query->where('id', $domainId);
        } elseif ($domainName) {
            $fullDomain = $domainName;
            $parts = explode('.', $fullDomain, 2);
            if (count($parts) === 2) {
                $query->where('domain_name', $parts[0])
                      ->where('tld', '.' . $parts[1]);
            } else {
                $query->where('domain_name', $fullDomain);
            }
        } else {
            abort(403, 'Bu servis bu domain için aktif değil.');
        }

        $domain = $query->first();

        if (!$domain) {
            abort(403, 'Bu servis bu domain için aktif değil.');
        }

        return view('embed.try', compact('domain', 'garmentUrl', 'ref'));
    }

    public function startGeneration(Request $request)
    {
        $request->validate([
            'domain_id'    => 'required|integer',
            'garment_url'  => 'required|url|max:2048',
            'person_image' => 'required|image|max:10240|mimes:jpg,jpeg,png,webp',
        ]);

        $domain = Domain::where('id', $request->domain_id)
            ->where('status', 'active')
            ->first();

        if (!$domain) {
            return response()->json(['success' => false, 'message' => 'Domain aktif değil veya bulunamadı.'], 403);
        }

        $owner = $domain->user;

        if ($owner->credit_balance < 1) {
            return response()->json(['success' => false, 'message' => 'Domain sahibinin kredisi yetersiz. Lütfen site yöneticisiyle iletişime geçin.'], 422);
        }

        try {
            $runId  = uniqid();
            $folder = 'generations/embed/' . $domain->id . '/' . $runId;

            $personPath = $request->file('person_image')->storeAs(
                $folder, 'person.' . $request->file('person_image')->extension(), 'public'
            );

            $garmentContents = @file_get_contents($request->garment_url);
            if ($garmentContents === false) {
                return response()->json(['success' => false, 'message' => 'Kıyafet görseli indirilemedi.'], 422);
            }

            $garmentPath = $folder . '/garment.jpg';
            Storage::disk('public')->put($garmentPath, $garmentContents);

            $generation = Generation::create([
                'user_id'            => $owner->id,
                'domain_id'          => $domain->id,
                'source'             => 'embed',
                'person_image_path'  => $personPath,
                'garment_image_path' => $garmentPath,
                'garment_url'        => $request->garment_url,
                'status'             => 'processing',
                'progress'           => 0,
                'started_at'         => now(),
                'expires_at'         => now()->addDays(7),
            ]);

            $result = $this->wiro->startJob($personPath, $garmentPath);

            if (!$result['success']) {
                $generation->update(['status' => 'failed', 'error_message' => $result['error'], 'completed_at' => now()]);
                return response()->json(['success' => false, 'message' => $result['error']], 500);
            }

            $generation->update(['wiro_job_id' => $result['task_id']]);

            $owner->deductCredits(1, 'usage', 'Embed widget AI deneme: ' . $domain->full_domain);

            $domain->increment('total_requests');
            $domain->update(['last_request_at' => now()]);

            return response()->json([
                'success'       => true,
                'generation_id' => $generation->id,
            ]);

        } catch (\Exception $e) {
            Log::error('EmbedController startGeneration exception', ['message' => $e->getMessage()]);
            if (isset($generation)) {
                $generation->update(['status' => 'failed', 'error_message' => $e->getMessage(), 'completed_at' => now()]);
            }
            return response()->json(['success' => false, 'message' => 'Sunucu hatası.'], 500);
        }
    }

    public function pollStatus(Request $request, int $id)
    {
        $generation = Generation::where('id', $id)
            ->where('source', 'embed')
            ->firstOrFail();

        if ($generation->status === 'completed') {
            return response()->json([
                'success'    => true,
                'status'     => 'completed',
                'progress'   => 100,
                'result_url' => $generation->result_image_path
                    ? Storage::disk('public')->url($generation->result_image_path)
                    : $generation->result_image_url,
            ]);
        }

        if ($generation->status === 'failed') {
            return response()->json(['success' => true, 'status' => 'failed', 'progress' => 0]);
        }

        if ($generation->started_at && now()->diffInSeconds($generation->started_at) > 90) {
            $generation->update(['status' => 'failed', 'progress' => 0, 'error_message' => 'Zaman aşımı', 'completed_at' => now()]);
            $generation->user->addCredits(1, 'refund', 'Embed zaman aşımı - kredi iadesi');
            return response()->json(['success' => true, 'status' => 'failed', 'progress' => 0, 'message' => 'Zaman aşımı.']);
        }

        $result = $this->wiro->getJobStatus($generation->wiro_job_id ?? '');

        if (!$result['success']) {
            return response()->json(['success' => false, 'status' => 'processing', 'progress' => $generation->progress]);
        }

        $generation->update(['progress' => $result['progress']]);

        if ($result['status'] === 'completed' && $result['result_url']) {
            $folder     = str_replace('\\', '/', dirname($generation->person_image_path));
            $resultPath = $this->downloadResult($result['result_url'], $folder . '/result.png');

            Storage::disk('public')->delete([$generation->person_image_path, $generation->garment_image_path]);

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

        if ($result['status'] === 'failed') {
            $generation->update(['status' => 'failed', 'progress' => 0, 'completed_at' => now()]);
            $generation->user->addCredits(1, 'refund', 'Embed Wiro başarısız - kredi iadesi');
            return response()->json(['success' => true, 'status' => 'failed', 'progress' => 0]);
        }

        return response()->json(['success' => true, 'status' => 'processing', 'progress' => $result['progress']]);
    }

    private function downloadResult(string $url, string $savePath): ?string
    {
        try {
            $contents = file_get_contents($url);
            if ($contents === false) return null;
            Storage::disk('public')->put($savePath, $contents);
            return $savePath;
        } catch (\Exception $e) {
            Log::error('EmbedController downloadResult exception', ['message' => $e->getMessage()]);
            return null;
        }
    }
}
