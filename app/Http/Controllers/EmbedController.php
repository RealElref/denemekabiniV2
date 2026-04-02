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

        $domainId = $domain->id;

        $js = <<<JS
(function() {
  'use strict';
  if (window.__WiroEmbed) return;
  window.__WiroEmbed = true;

  var BASE = (function() {
    var scripts = document.querySelectorAll('script[src*="embed.js"]');
    if (scripts.length) {
      var url = new URL(scripts[scripts.length - 1].src);
      return url.origin;
    }
    return window.location.origin;
  })();
  var DOMAIN_ID = {$domainId};

  var BTN_LABEL  = 'AI ile Dene';
  var BTN_ICON   = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M12 2C9.5 2 8 4 8 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-3s-1.5-2-4-2z"/><circle cx="12" cy="13" r="3" stroke="currentColor" stroke-width="2"/></svg>';
  var LOADER_HTML = '<span class="wiro-loader" aria-label="Yükleniyor"></span>';

  function getImgSrc(img) {
    return img.src
      || img.getAttribute('data-src')
      || img.getAttribute('data-lazy-src')
      || img.getAttribute('data-original')
      || img.getAttribute('data-srcset')
      || '';
  }

  function scoreImg(img) {
    var score = 0;
    var src = (getImgSrc(img) || '').toLowerCase();
    var alt = (img.alt || '').toLowerCase();
    var cls = ((img.className || '') + ' ' + (img.getAttribute('data-class') || '')).toLowerCase();
    var par = img.parentElement ? (img.parentElement.className || '').toLowerCase() : '';

    if (!src || src.startsWith('data:') || src.includes('logo') || src.includes('icon') || src.includes('sprite')) return -1;

    var w = img.naturalWidth || img.width || parseInt(img.getAttribute('width') || '0');
    var h = img.naturalHeight || img.height || parseInt(img.getAttribute('height') || '0');
    if (w > 0 && h > 0 && (w < 80 || h < 80)) return -1;

    if (w > 200 && h > 200) score += 10;
    if (w > 400 && h > 400) score += 10;

    var keywords = ['product','kiyafet','clothing','outfit','fashion','model','wear','giyim','urun','garment','apparel','item'];
    keywords.forEach(function(k) {
      if (src.includes(k) || alt.includes(k) || cls.includes(k) || par.includes(k)) score += 5;
    });

    if (img.closest('[class*="product"]') || img.closest('[class*="item"]') || img.closest('[class*="card"]')) score += 8;
    if (img.closest('main') || img.closest('[role="main"]')) score += 3;
    if (img.closest('header') || img.closest('footer') || img.closest('nav')) score -= 5;

    return score;
  }

  function findBestImage() {
    var imgs = Array.from(document.querySelectorAll('img'));
    var best = null, bestScore = -999;
    imgs.forEach(function(img) {
      var s = scoreImg(img);
      if (s > bestScore) { bestScore = s; best = img; }
    });
    return bestScore >= 0 ? best : null;
  }

  function findProductImages() {
    var results = [];
    var targets = document.querySelectorAll('[data-wiro-garment], [data-wiro-target]');
    if (targets.length) return null;

    var selectors = [
      '.product-image img', '.product__image img', '.product-photo img',
      '[class*="product"] img', '[class*="gallery"] img',
      '[data-product-image] img', 'img[itemprop="image"]',
      '.swiper-slide img', '.slick-slide img', '.carousel-item img'
    ];

    selectors.forEach(function(sel) {
      try {
        document.querySelectorAll(sel).forEach(function(img) {
          if (results.indexOf(img) === -1 && scoreImg(img) >= 0) results.push(img);
        });
      } catch(e) {}
    });

    return results.length ? results : null;
  }

  function createStyles() {
    if (document.getElementById('wiro-styles')) return;
    var style = document.createElement('style');
    style.id = 'wiro-styles';
    style.textContent = [
      '.wiro-btn{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:linear-gradient(135deg,#3B82F6,#1D4ED8);color:#fff !important;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 15px rgba(59,130,246,0.4);transition:all .2s;font-family:inherit;letter-spacing:.01em;z-index:9999;position:relative;text-decoration:none !important;line-height:1.4}',
      '.wiro-btn:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(59,130,246,0.5);color:#fff !important}',
      '.wiro-btn:disabled{opacity:.7;cursor:not-allowed;transform:none}',
      '.wiro-btn svg{flex-shrink:0;pointer-events:none}',
      '.wiro-loader{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:wiro-spin .7s linear infinite;flex-shrink:0}',
      '@keyframes wiro-spin{to{transform:rotate(360deg)}}',
      '.wiro-wrap{margin-top:8px;display:block}'
    ].join('');
    document.head.appendChild(style);
  }

  function createButton(garmentUrl) {
    var btn = document.createElement('button');
    btn.className = 'wiro-btn';
    btn.setAttribute('type', 'button');
    btn.setAttribute('data-wiro-btn', '1');
    btn.setAttribute('data-garment-url', garmentUrl || '');
    btn.innerHTML = BTN_ICON + BTN_LABEL;
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      handleClick(btn, garmentUrl);
    });
    return btn;
  }

  function openTryPage(garmentUrl) {
    var params = new URLSearchParams({
      domain_id: DOMAIN_ID,
      ref: window.location.href
    });
    if (garmentUrl) params.set('garment_url', garmentUrl);
    window.open(BASE + '/dene?' + params.toString(), '_blank', 'width=520,height=700,scrollbars=yes,resizable=yes');
  }

  function resetBtn(btn, garmentUrl) {
    btn.disabled = false;
    btn.innerHTML = BTN_ICON + BTN_LABEL;
    btn.setAttribute('data-garment-url', garmentUrl || '');
  }

  function handleClick(btn, garmentUrl) {
    var finalGarment = garmentUrl || btn.getAttribute('data-garment-url') || '';

    if (!finalGarment) {
      var bestImg = findBestImage();
      finalGarment = bestImg ? getImgSrc(bestImg) : '';
    }

    btn.disabled = true;
    btn.innerHTML = LOADER_HTML + 'Açılıyor...';

    openTryPage(finalGarment);

    setTimeout(function() { resetBtn(btn, finalGarment); }, 1500);
  }

  function injectForTargets() {
    var targets = document.querySelectorAll('[data-wiro-target], [data-wiro-garment]');
    targets.forEach(function(el) {
      if (el.querySelector('[data-wiro-btn]')) return;
      var garmentUrl = el.getAttribute('data-wiro-garment') || (el.tagName === 'IMG' ? getImgSrc(el) : '');
      var btn = createButton(garmentUrl);
      if (el.getAttribute('data-wiro-target')) {
        var t = document.querySelector(el.getAttribute('data-wiro-target'));
        if (t && !t.querySelector('[data-wiro-btn]')) t.appendChild(btn);
      } else {
        var wrap = document.createElement('div');
        wrap.className = 'wiro-wrap';
        wrap.appendChild(btn);
        el.parentElement.insertBefore(wrap, el.nextSibling);
      }
    });
    return targets.length > 0;
  }

  function injectForProductImages() {
    var productImgs = findProductImages();
    if (!productImgs || !productImgs.length) return false;

    productImgs.forEach(function(img) {
      var parent = img.parentElement;
      if (!parent) return;
      if (parent.querySelector('[data-wiro-btn]')) return;

      var garmentUrl = getImgSrc(img);
      var btn = createButton(garmentUrl);
      var wrap = document.createElement('div');
      wrap.className = 'wiro-wrap';
      wrap.appendChild(btn);

      var imgParent = img.parentElement;
      imgParent.style.position = imgParent.style.position || 'relative';
      imgParent.insertBefore(wrap, img.nextSibling);
    });
    return true;
  }

  function injectFallback() {
    var bestImg = findBestImage();
    if (!bestImg) {
      var floatBtn = createButton('');
      floatBtn.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:99999;box-shadow:0 8px 30px rgba(59,130,246,0.5)';
      document.body.appendChild(floatBtn);
      return;
    }

    var parent = bestImg.parentElement;
    if (parent && !parent.querySelector('[data-wiro-btn]')) {
      var garmentUrl = getImgSrc(bestImg);
      var btn = createButton(garmentUrl);
      var wrap = document.createElement('div');
      wrap.className = 'wiro-wrap';
      wrap.appendChild(btn);
      parent.style.position = parent.style.position || 'relative';
      parent.insertBefore(wrap, bestImg.nextSibling);
    }
  }

  function injectButtons() {
    if (document.querySelectorAll('[data-wiro-btn]').length) return;
    if (injectForTargets()) return;
    if (injectForProductImages()) return;
    injectFallback();
  }

  function init() {
    createStyles();
    injectButtons();

    setTimeout(function() {
      if (!document.querySelectorAll('[data-wiro-btn]').length) {
        injectButtons();
      }
    }, 1500);

    setTimeout(function() {
      if (!document.querySelectorAll('[data-wiro-btn]').length) {
        injectButtons();
      }
    }, 3500);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
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

        if ($domainId) {
            $domain = Domain::where('id', $domainId)
                ->where('status', 'active')
                ->first();
        } elseif ($domainName) {
            $lower = strtolower($domainName);
            $parts = explode('.', $lower, 2);

            $domain = null;

            if (count($parts) === 2) {
                $domain = Domain::whereRaw('LOWER(domain_name) = ?', [$parts[0]])
                    ->whereRaw('LOWER(tld) = ?', ['.' . $parts[1]])
                    ->where('status', 'active')
                    ->first();
            }

            if (!$domain) {
                $domain = Domain::whereRaw("LOWER(CONCAT(domain_name, tld)) = ?", [$lower])
                    ->where('status', 'active')
                    ->first();
            }

            if (!$domain) {
                $allDomains = Domain::select('id', 'domain_name', 'tld', 'status')->get();
                Log::warning('tryPage domain not found', [
                    'requested'  => $domainName,
                    'all_active' => $allDomains->where('status', 'active')->values()->toArray(),
                    'all'        => $allDomains->toArray(),
                ]);
            }
        } else {
            abort(403, 'Bu servis bu domain için aktif değil.');
        }

        if (!$domain) {
            abort(403, 'Bu servis bu domain için aktif değil.');
        }

        return view('embed.try', compact('domain', 'garmentUrl', 'ref'));
    }

    public function startGeneration(Request $request)
    {
        $request->validate([
            'domain_id'     => 'required|integer',
            'garment_url'   => 'nullable|url|max:2048',
            'garment_image' => 'nullable|image|max:10240|mimes:jpg,jpeg,png,webp',
            'person_image'  => 'required|image|max:10240|mimes:jpg,jpeg,png,webp',
        ]);

        if (!$request->filled('garment_url') && !$request->hasFile('garment_image')) {
            return response()->json(['success' => false, 'message' => 'Kıyafet görseli veya dosyası gereklidir.'], 422);
        }

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

            if ($request->hasFile('garment_image')) {
                $garmentPath = $request->file('garment_image')->storeAs(
                    $folder, 'garment.' . $request->file('garment_image')->extension(), 'public'
                );
            } else {
                $garmentContents = @file_get_contents($request->garment_url);
                if ($garmentContents === false) {
                    return response()->json(['success' => false, 'message' => 'Kıyafet görseli indirilemedi.'], 422);
                }
                $garmentPath = $folder . '/garment.jpg';
                Storage::disk('public')->put($garmentPath, $garmentContents);
            }

            $generation = Generation::create([
                'user_id'            => $owner->id,
                'domain_id'          => $domain->id,
                'source'             => 'embed',
                'person_image_path'  => $personPath,
                'garment_image_path' => $garmentPath,
                'garment_url'        => $request->garment_url ?? null,
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
