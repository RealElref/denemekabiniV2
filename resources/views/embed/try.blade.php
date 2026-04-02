<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Deneme Kabini &ndash; {{ $domain->full_domain }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#0a0f1a;color:#e2e8f0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem}
.card{background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:1.75rem;width:100%;max-width:420px;box-shadow:0 20px 60px rgba(0,0,0,0.5)}
.logo-row{display:flex;align-items:center;gap:0.6rem;margin-bottom:1.5rem}
.logo-icon{width:36px;height:36px;background:linear-gradient(135deg,#3B82F6,#1D4ED8);border-radius:10px;display:flex;align-items:center;justify-content:center}
.logo-text{font-size:0.82rem;color:rgba(255,255,255,0.45)}
.logo-domain{font-size:0.88rem;font-weight:700;color:rgba(255,255,255,0.85)}
h2{font-size:1.1rem;font-weight:700;color:#f8fafc;margin-bottom:0.3rem}
.sub{font-size:0.82rem;color:rgba(255,255,255,0.45);margin-bottom:1.25rem;line-height:1.5}
.section-label{font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:.06em;margin-bottom:0.5rem}
.garment-preview{width:100%;border-radius:10px;border:1px solid rgba(255,255,255,0.08);max-height:160px;object-fit:cover;margin-bottom:1.25rem;display:block}
.upload-zone{border:2px dashed rgba(255,255,255,0.15);border-radius:10px;padding:1.25rem 1rem;text-align:center;cursor:pointer;transition:all .2s;position:relative;background:rgba(0,0,0,0.2)}
.upload-zone:hover{border-color:rgba(59,130,246,0.5);background:rgba(59,130,246,0.05)}
.upload-zone .uz-icon{font-size:1.75rem;margin-bottom:0.4rem}
.upload-zone .uz-label{font-size:0.85rem;color:rgba(255,255,255,0.5)}
.upload-zone .uz-sub{font-size:0.75rem;color:rgba(255,255,255,0.3);margin-top:0.2rem}
.thumb-preview{width:100%;border-radius:10px;max-height:160px;object-fit:cover;display:none;margin-top:0.6rem}
.divider{height:1px;background:rgba(255,255,255,0.07);margin:1.1rem 0}
.btn-primary{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:0.8rem;border:none;border-radius:10px;font-size:0.9rem;font-weight:700;cursor:pointer;transition:all .2s;margin-top:1rem;background:linear-gradient(135deg,#3B82F6,#1D4ED8);color:#fff;box-shadow:0 4px 15px rgba(59,130,246,0.3)}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(59,130,246,0.4)}
.btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none}
.progress-wrap{margin-top:1.25rem;display:none}
.progress-track{height:6px;background:rgba(255,255,255,0.08);border-radius:999px;overflow:hidden}
.progress-fill{height:100%;background:linear-gradient(90deg,#3B82F6,#60A5FA);border-radius:999px;transition:width .4s ease;width:0%}
.progress-row{display:flex;justify-content:space-between;font-size:0.75rem;color:rgba(255,255,255,0.4);margin-top:0.4rem}
.alert-err{border-radius:8px;padding:0.7rem 0.9rem;font-size:0.82rem;margin-top:0.85rem;display:none;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#FCA5A5}
.spinner{display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;flex-shrink:0}
@keyframes spin{to{transform:rotate(360deg)}}
.result-actions{display:flex;justify-content:center;gap:0.5rem;flex-wrap:wrap;margin-top:0.75rem}
.btn-green{display:inline-flex;align-items:center;gap:6px;padding:0.5rem 1.2rem;background:rgba(52,211,153,0.12);border:1px solid rgba(52,211,153,0.3);border-radius:8px;color:#34D399;font-size:0.82rem;font-weight:600;text-decoration:none;cursor:pointer}
.btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:0.5rem 1.2rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.12);border-radius:8px;color:rgba(255,255,255,0.5);font-size:0.82rem;font-weight:600;cursor:pointer}
</style>
</head>
<body>
<div class="card">

  <div class="logo-row">
    <div class="logo-icon">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
        <path stroke-linecap="round" d="M12 2C9.5 2 8 4 8 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-3s-1.5-2-4-2z"/>
        <circle cx="12" cy="13" r="3"/>
      </svg>
    </div>
    <div>
      <div class="logo-text">AI Deneme Kabini</div>
      <div class="logo-domain">{{ $domain->full_domain }}</div>
    </div>
  </div>

  <div id="step-upload">
    <h2>Kıyafeti Üstünde Dene</h2>
    <p class="sub">Kendi fotoğrafını yükle, yapay zeka kıyafeti üstüne giydirsin.</p>

    @if($garmentUrl)
      <div class="section-label">Kıyafet</div>
      <img src="{{ $garmentUrl }}" alt="Kıyafet" class="garment-preview" onerror="this.style.display='none'">
    @else
      <div class="section-label">Kıyafet Görseli</div>
      <div class="upload-zone" id="garment-drop">
        <input type="file" id="garment-input" accept="image/jpeg,image/png,image/webp"
               style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%">
        <div class="uz-icon">&#128247;</div>
        <div class="uz-label">Kıyafet fotoğrafı seç</div>
        <div class="uz-sub">JPG, PNG veya WebP &bull; Maks 10 MB</div>
      </div>
      <img id="garment-preview" src="" alt="Kıyafet Önizleme" class="thumb-preview">
      <div class="divider"></div>
    @endif

    <div class="section-label">Kişi Fotoğrafı</div>
    <div class="upload-zone" id="person-drop">
      <input type="file" id="person-input" accept="image/jpeg,image/png,image/webp"
             style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%">
      <div class="uz-icon">&#128100;</div>
      <div class="uz-label">Kişi fotoğrafı seç</div>
      <div class="uz-sub">JPG, PNG veya WebP &bull; Maks 10 MB</div>
    </div>
    <img id="person-preview" src="" alt="Önizleme" class="thumb-preview">

    <div id="alert-err" class="alert-err"></div>

    <button class="btn-primary" id="start-btn">
      Yapay Zeka ile Dene
    </button>

    <div class="progress-wrap" id="progress-wrap">
      <div class="progress-track">
        <div class="progress-fill" id="progress-fill"></div>
      </div>
      <div class="progress-row">
        <span id="progress-status">İşleniyor...</span>
        <span id="progress-pct">0%</span>
      </div>
    </div>
  </div>

  <div id="step-result" style="display:none">
    <h2 style="margin-bottom:0.75rem">Sonuç Hazır!</h2>
    <img id="result-img" src="" alt="Sonuç"
         style="width:100%;border-radius:10px;border:1px solid rgba(255,255,255,0.1);max-height:340px;object-fit:cover">
    <div class="result-actions">
      <a id="result-download" href="#" download="wiro-result.png" class="btn-green">İndir</a>
      <button id="reset-btn" class="btn-ghost">Tekrar Dene</button>
    </div>
  </div>

</div>

<script>
(function () {
  var DOMAIN_ID   = {{ $domain->id }};
  var GARMENT_URL = @json($garmentUrl);
  var BASE_URL    = window.location.origin;
  var CSRF        = '{{ csrf_token() }}';

  var personFile   = null;
  var garmentFile  = null;
  var pollingTimer = null;
  var genId        = null;

  function setupFileInput(inputId, previewId, onPick) {
    var input = document.getElementById(inputId);
    if (!input) return;
    input.addEventListener('change', function () {
      if (input.files && input.files[0]) onPick(input.files[0]);
    });
  }

  function showPreview(file, previewId) {
    var reader = new FileReader();
    reader.onload = function (e) {
      var img = document.getElementById(previewId);
      img.src = e.target.result;
      img.style.display = 'block';
    };
    reader.readAsDataURL(file);
  }

  function showErr(msg) {
    var el = document.getElementById('alert-err');
    el.innerText = msg;
    el.style.display = 'block';
  }

  function hideErr() {
    document.getElementById('alert-err').style.display = 'none';
  }

  function setProgress(pct, status) {
    document.getElementById('progress-fill').style.width = pct + '%';
    document.getElementById('progress-pct').innerText    = pct + '%';
    document.getElementById('progress-status').innerText = status;
  }

  function getStatusText(pct) {
    if (pct < 20) return 'Kuyrukta bekleniyor...';
    if (pct < 40) return 'Ön işleme başladı...';
    if (pct < 60) return "GPU'ya atandı...";
    if (pct < 80) return 'Görsel oluşturuluyor...';
    if (pct < 95) return 'Son rötuşlar yapılıyor...';
    return 'Tamamlanıyor...';
  }

  function setBtnLoading(loading) {
    var btn = document.getElementById('start-btn');
    btn.disabled = loading;
    btn.innerText = loading ? 'Yükleniyor...' : 'Yapay Zeka ile Dene';
  }

  function showResult(url) {
    document.getElementById('step-upload').style.display = 'none';
    document.getElementById('result-img').src            = url;
    document.getElementById('result-download').href      = url;
    document.getElementById('step-result').style.display = 'block';
  }

  function startPolling() {
    pollingTimer = setInterval(function () {
      fetch(BASE_URL + '/embed/status/' + genId, { credentials: 'include' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.status === 'completed') {
            clearInterval(pollingTimer);
            setProgress(100, 'Tamamlandı!');
            setTimeout(function () { showResult(data.result_url); }, 400);
          } else if (data.status === 'failed') {
            clearInterval(pollingTimer);
            setBtnLoading(false);
            document.getElementById('progress-wrap').style.display = 'none';
            showErr(data.message || 'İşlem başarısız oldu.');
          } else {
            var pct = data.progress || 0;
            setProgress(pct, getStatusText(pct));
          }
        })
        .catch(function (e) { console.error('Poll error', e); });
    }, 3000);
  }

  function startEmbed() {
    hideErr();
    if (!personFile) { showErr('Lütfen kişi fotoğrafı seçin.'); return; }
    if (!GARMENT_URL && !garmentFile) { showErr('Lütfen kıyafet fotoğrafı seçin.'); return; }

    setBtnLoading(true);

    var fd = new FormData();
    fd.append('domain_id',    DOMAIN_ID);
    fd.append('person_image', personFile);
    fd.append('_token',       CSRF);

    if (GARMENT_URL) {
      fd.append('garment_url', GARMENT_URL);
    } else {
      fd.append('garment_image', garmentFile);
    }

    fetch(BASE_URL + '/embed/start', { method: 'POST', body: fd, credentials: 'include' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.success) {
          setBtnLoading(false);
          showErr(data.message || 'Bir hata oluştu.');
          return;
        }
        genId = data.generation_id;
        document.getElementById('progress-wrap').style.display = 'block';
        setProgress(5, 'İşlem başlatıldı...');
        startPolling();
      })
      .catch(function (e) {
        setBtnLoading(false);
        showErr('Bağlantı hatası: ' + e.message);
      });
  }

  function resetEmbed() {
    if (pollingTimer) clearInterval(pollingTimer);
    personFile  = null;
    garmentFile = null;
    genId       = null;

    var pInput = document.getElementById('person-input');
    if (pInput) pInput.value = '';
    var pPrev = document.getElementById('person-preview');
    if (pPrev) { pPrev.src = ''; pPrev.style.display = 'none'; }

    var gInput = document.getElementById('garment-input');
    if (gInput) gInput.value = '';
    var gPrev = document.getElementById('garment-preview');
    if (gPrev) { gPrev.src = ''; gPrev.style.display = 'none'; }

    setBtnLoading(false);
    setProgress(0, 'İşleniyor...');
    hideErr();
    document.getElementById('progress-wrap').style.display  = 'none';
    document.getElementById('step-result').style.display    = 'none';
    document.getElementById('step-upload').style.display    = 'block';
  }

  function setupDragDrop(dropId, inputId) {
    var zone = document.getElementById(dropId);
    if (!zone) return;
    zone.addEventListener('dragover', function (e) {
      e.preventDefault();
      zone.style.borderColor = 'rgba(59,130,246,.6)';
    });
    zone.addEventListener('dragleave', function () {
      zone.style.borderColor = '';
    });
    zone.addEventListener('drop', function (e) {
      e.preventDefault();
      zone.style.borderColor = '';
      var file = e.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) {
        var input = document.getElementById(inputId);
        var dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
      }
    });
  }

  setupFileInput('garment-input', 'garment-preview', function (f) {
    garmentFile = f;
    showPreview(f, 'garment-preview');
  });
  setupFileInput('person-input', 'person-preview', function (f) {
    personFile = f;
    showPreview(f, 'person-preview');
  });

  setupDragDrop('garment-drop', 'garment-input');
  setupDragDrop('person-drop', 'person-input');

  document.getElementById('start-btn').addEventListener('click', startEmbed);
  var resetBtn = document.getElementById('reset-btn');
  if (resetBtn) resetBtn.addEventListener('click', resetEmbed);
})();
</script>
</body>
</html>
