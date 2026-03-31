<script>
// ── TRY-ON MODAL ──────────────────────────────────────────────
let personFile    = null;
let garmentFile   = null;
let pollingTimer  = null;
let timeoutTimer  = null;
let currentGenId  = null;

function openTryOn() {
    document.getElementById('tryon-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeTryOn() {
    if (pollingTimer) clearInterval(pollingTimer);
    if (timeoutTimer) clearTimeout(timeoutTimer);
    document.getElementById('tryon-modal').style.display = 'none';
    document.body.style.overflow = '';
}

function resetTryOn() {
    personFile  = null;
    garmentFile = null;
    currentGenId = null;
    if (pollingTimer) clearInterval(pollingTimer);
    if (timeoutTimer) clearTimeout(timeoutTimer);

    document.getElementById('person-preview').style.display  = 'none';
    document.getElementById('garment-preview').style.display = 'none';
    document.getElementById('person-placeholder').style.display  = 'flex';
    document.getElementById('garment-placeholder').style.display = 'flex';
    document.getElementById('person-input').value  = '';
    document.getElementById('garment-input').value = '';
    document.getElementById('tryon-prompt').value  = '';
    document.getElementById('tryon-error').style.display = 'none';
    document.getElementById('progress-bar').style.width = '0%';
    document.getElementById('progress-text').innerText = '%0';

    showStep('upload');
}

function showStep(step) {
    document.getElementById('step-upload').style.display     = step === 'upload'     ? 'block' : 'none';
    document.getElementById('step-processing').style.display = step === 'processing' ? 'block' : 'none';
    document.getElementById('step-result').style.display     = step === 'result'     ? 'block' : 'none';
}

function previewImage(input, type) {
    if (!input.files || !input.files[0]) return;
    const file   = input.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const preview     = document.getElementById(type + '-preview');
        const placeholder = document.getElementById(type + '-placeholder');
        preview.src              = e.target.result;
        preview.style.display    = 'block';
        placeholder.style.display = 'none';
    };
    reader.readAsDataURL(file);

    if (type === 'person')  personFile  = file;
    if (type === 'garment') garmentFile = file;
}

function showError(msg) {
    const el = document.getElementById('tryon-error');
    el.innerText      = msg;
    el.style.display  = 'block';
}

async function startTryOn() {
    if (!personFile) {
        showError('Lütfen kişi fotoğrafı yükleyin.'); return;
    }
    if (!garmentFile) {
        showError('Lütfen kıyafet fotoğrafı yükleyin.'); return;
    }

    document.getElementById('tryon-error').style.display = 'none';
    document.getElementById('tryon-start-btn').disabled  = true;
    showStep('processing');
    updateProgress(5, 'Yükleniyor...');

    const formData = new FormData();
    formData.append('person_image',  personFile);
    formData.append('garment_image', garmentFile);
    formData.append('prompt', document.getElementById('tryon-prompt').value);
    formData.append('_token', '{{ csrf_token() }}');

    try {
        const res  = await fetch('/api/generations', {
            method: 'POST',
            body:   formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        });
        const data = await res.json();

        if (!data.success) {
            showStep('upload');
            document.getElementById('tryon-start-btn').disabled = false;
            if (data.error === 'insufficient_credits') {
                showError('Yetersiz kredi! Lütfen kredi satın alın.');
                switchTabById('tab-credits');
                closeTryOn();
            } else {
                showError(data.message || 'Bir hata oluştu.');
            }
            return;
        }

        currentGenId = data.generation_id;
        startPolling(currentGenId);

        timeoutTimer = setTimeout(() => {
            if (pollingTimer) clearInterval(pollingTimer);
            showStep('upload');
            document.getElementById('tryon-start-btn').disabled = false;
            showError('İşlem zaman aşımına uğradı. Krediniz iade edildi.');
        }, 65000);

    } catch (err) {
        showStep('upload');
        document.getElementById('tryon-start-btn').disabled = false;
        showError('Bağlantı hatası: ' + err.message);
    }
}

function startPolling(genId) {
    updateProgress(10, 'İşlem başlatıldı...');

    pollingTimer = setInterval(async () => {
        try {
            const res  = await fetch('/api/generations/' + genId + '/status', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
            });
            const data = await res.json();

            if (data.status === 'completed') {
                clearInterval(pollingTimer);
                clearTimeout(timeoutTimer);
                updateProgress(100, 'Tamamlandı!');

                setTimeout(() => {
                    document.getElementById('result-image').src    = data.result_url;
                    document.getElementById('result-download').href = data.result_url;
                    showStep('result');
                    document.getElementById('tryon-start-btn').disabled = false;
                    refreshStudio();
                }, 500);

            } else if (data.status === 'failed') {
                clearInterval(pollingTimer);
                clearTimeout(timeoutTimer);
                showStep('upload');
                document.getElementById('tryon-start-btn').disabled = false;
                showError(data.message || 'İşlem başarısız. Krediniz iade edildi.');

            } else {
                const progress = data.progress || 0;
                updateProgress(progress, getStatusText(progress));
            }
        } catch (err) {
            console.error('Polling error:', err);
        }
    }, 3000);
}

function updateProgress(pct, text) {
    document.getElementById('progress-bar').style.width  = pct + '%';
    document.getElementById('progress-text').innerText   = '%' + pct;
    document.getElementById('progress-status').innerText = text;
}

function getStatusText(progress) {
    if (progress < 20)  return 'Kuyrukta bekleniyor...';
    if (progress < 40)  return 'Ön işleme başladı...';
    if (progress < 60)  return 'GPU\'ya atandı...';
    if (progress < 80)  return 'Görsel oluşturuluyor...';
    if (progress < 95)  return 'Son rötuşlar yapılıyor...';
    return 'Tamamlanıyor...';
}

function cancelTryOn() {
    if (pollingTimer) clearInterval(pollingTimer);
    if (timeoutTimer) clearTimeout(timeoutTimer);
    showStep('upload');
    document.getElementById('tryon-start-btn').disabled = false;
}

function switchTabById(tabId) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    const tabIndex = {'tab-history':0,'tab-transactions':1,'tab-domains':2,'tab-credits':3};
    const idx = tabIndex[tabId];
    if (idx !== undefined) document.querySelectorAll('.tab-btn')[idx].classList.add('active');
    sessionStorage.setItem('activeTab', tabId);
}

function refreshStudio() {
    fetch('/panel?gen_page=1', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc    = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('tab-history');
            if (newContent) {
                document.getElementById('tab-history').innerHTML = newContent.innerHTML;
            }
        }).catch(() => {});
}

// Drag & Drop desteği
['person-drop', 'garment-drop'].forEach(id => {
    const el   = document.getElementById(id);
    const type = id.replace('-drop', '');
    if (!el) return;

    el.addEventListener('dragover', e => {
        e.preventDefault();
        el.style.borderColor = 'var(--primary)';
        el.style.background  = 'rgba(129,140,248,0.1)';
    });
    el.addEventListener('dragleave', () => {
        el.style.borderColor = 'var(--glass-border)';
        el.style.background  = 'rgba(0,0,0,0.2)';
    });
    el.addEventListener('drop', e => {
        e.preventDefault();
        el.style.borderColor = 'var(--glass-border)';
        el.style.background  = 'rgba(0,0,0,0.2)';
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const input = document.getElementById(type + '-input');
            const dt    = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            previewImage(input, type);
        }
    });
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeTryOn();
});

function copyRef(el) {
    const text = el.innerText.trim();
    navigator.clipboard.writeText(text).then(() => {
        const orig = el.innerText;
        el.style.background = 'rgba(52,211,153,0.2)';
        el.style.color = '#34D399';
        el.style.borderColor = '#34D399';
        el.innerText = '{{ __("copied") }}';
        setTimeout(() => {
            el.style.background = '';
            el.style.color = '';
            el.style.borderColor = '';
            el.innerText = orig;
        }, 2000);
    });
}

function switchTab(event, tabId) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    event.currentTarget.classList.add('active');
    document.getElementById(tabId).classList.add('active');
    sessionStorage.setItem('activeTab', tabId);
}

document.addEventListener('DOMContentLoaded', function() {
    const savedTab = sessionStorage.getItem('activeTab');
    if (savedTab && document.getElementById(savedTab)) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.getElementById(savedTab).classList.add('active');
        const tabIndex = {
            'tab-history': 0,
            'tab-transactions': 1,
            'tab-domains': 2,
            'tab-credits': 3
        };
        const idx = tabIndex[savedTab];
        if (idx !== undefined) {
            document.querySelectorAll('.tab-btn')[idx].classList.add('active');
        }
    }
});

function updateCustomPrice() {
    const amount = parseInt(document.getElementById('custom-credit-amount').value) || 1;
    const price = (amount * 4.9).toFixed(2).replace('.', ',');
    document.getElementById('custom-credit-price').innerText = price + ' ₺';
    document.getElementById('custom-credit-btn').href = '{{ route("dashboard.credits") }}?amount=' + amount;
}

// ── DOMAIN MODAL ──────────────────────────────────────────────
let selectedYear = 1;

function openDomainModal() {
    document.getElementById('domain-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.getElementById('domain-modal-error').style.display   = 'none';
    document.getElementById('domain-modal-success').style.display = 'none';
    document.getElementById('domain-name-input').value = '';
    document.getElementById('domain-tld-input').value  = '.com';
    document.getElementById('domain-preview').innerText = '';
    selectedYear = 1;
    document.querySelectorAll('.year-btn').forEach(b => {
        b.dataset.active = b.dataset.year == '1' ? '1' : '0';
        b.style.borderColor = b.dataset.year == '1' ? 'var(--primary)' : 'var(--glass-border)';
        b.style.color       = b.dataset.year == '1' ? 'var(--text-bright)' : 'var(--text-muted)';
        b.style.background  = b.dataset.year == '1' ? 'rgba(59,130,246,0.12)' : '';
    });
    const btn = document.getElementById('domain-submit-btn');
    if (btn) { btn.disabled = false; btn.innerText = '{{ __("domain_submit") }}'; }
}

function closeDomainModal() {
    document.getElementById('domain-modal').style.display = 'none';
    document.body.style.overflow = '';
}

function selectYear(yr) {
    selectedYear = yr;
    document.querySelectorAll('.year-btn').forEach(b => {
        const active = parseInt(b.dataset.year) === yr;
        b.dataset.active    = active ? '1' : '0';
        b.style.borderColor = active ? 'var(--primary)' : 'var(--glass-border)';
        b.style.color       = active ? 'var(--text-bright)' : 'var(--text-muted)';
        b.style.background  = active ? 'rgba(59,130,246,0.12)' : '';
    });
}

function updateYearSelect() {}

(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('domain-name-input');
        const tldInput  = document.getElementById('domain-tld-input');
        if (nameInput && tldInput) {
            function updatePreview() {
                const name = nameInput.value.trim();
                const tld  = tldInput.value;
                const prev = document.getElementById('domain-preview');
                prev.innerText = name ? name + tld : '';
            }
            nameInput.addEventListener('input', updatePreview);
            tldInput.addEventListener('change', updatePreview);
        }
    });
})();

async function submitDomain(e) {
    e.preventDefault();
    const name  = document.getElementById('domain-name-input').value.trim();
    const tld   = document.getElementById('domain-tld-input').value;
    const errEl = document.getElementById('domain-modal-error');
    const sucEl = document.getElementById('domain-modal-success');
    errEl.style.display = 'none';
    sucEl.style.display = 'none';

    if (!name) {
        errEl.innerText     = '{{ __("domain_name_required") }}';
        errEl.style.display = 'block';
        return;
    }
    if (!/^[a-z0-9\-]+$/i.test(name)) {
        errEl.innerText     = '{{ __("domain_name_invalid") }}';
        errEl.style.display = 'block';
        return;
    }

    const btn = document.getElementById('domain-submit-btn');
    btn.disabled  = true;
    btn.innerText = '{{ __("processing") }}';

    try {
        const res  = await fetch('/api/domains', {
            method:  'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ domain_name: name, tld, registration_years: selectedYear }),
        });
        const data = await res.json();

        if (!data.success) {
            errEl.innerText     = data.message || '{{ __("error_generic") }}';
            errEl.style.display = 'block';
            btn.disabled  = false;
            btn.innerText = '{{ __("domain_submit") }}';
            return;
        }

        sucEl.innerText     = data.message;
        sucEl.style.display = 'block';
        btn.innerText       = '✓';

        setTimeout(() => {
            closeDomainModal();
            loadPage('/panel', 'tab-domains', 'tab-domains');
        }, 1400);

    } catch (err) {
        errEl.innerText     = '{{ __("error_generic") }}';
        errEl.style.display = 'block';
        btn.disabled  = false;
        btn.innerText = '{{ __("domain_submit") }}';
    }
}

async function deleteDomain(id, btn) {
    if (!confirm('{{ __("domain_delete_confirm") }}')) return;
    btn.disabled = true;
    try {
        const res  = await fetch('/api/domains/' + id, {
            method:  'DELETE',
            headers: {
                'X-CSRF-TOKEN':     '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });
        const data = await res.json();
        if (data.success) {
            btn.closest('tr').style.opacity    = '0';
            btn.closest('tr').style.transition = 'opacity 0.3s';
            setTimeout(() => loadPage('/panel', 'tab-domains', 'tab-domains'), 350);
        }
    } catch {}
}

function copyDomainKey(el) {
    const key = el.dataset.key;
    navigator.clipboard.writeText(key).then(() => {
        const orig = el.innerText;
        el.style.color       = '#34D399';
        el.style.borderColor = '#34D399';
        el.innerText         = '{{ __("copied") }}';
        setTimeout(() => {
            el.style.color       = '';
            el.style.borderColor = '';
            el.innerText         = orig;
        }, 1800);
    });
}

function adjustCredit(delta) {
    const input = document.getElementById('custom-credit-amount');
    const val = Math.max(1, Math.min(1000, (parseInt(input.value) || 1) + delta));
    input.value = val;
    updateCustomPrice();
}

function setCredit(val) {
    document.getElementById('custom-credit-amount').value = val;
    updateCustomPrice();
}

function loadPage(url, targetId, tabId) {
    sessionStorage.setItem('activeTab', tabId);
    
    const container = document.getElementById(targetId);
    container.style.opacity = '0.5';
    container.style.transition = 'opacity 0.2s';

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.getElementById(targetId);
        if (newContent) {
            container.innerHTML = newContent.innerHTML;
            container.style.opacity = '1';
        }
    })
    .catch(() => {
        container.style.opacity = '1';
    });
}

function changePerPage(amount) {
    // Mevcut sayfanın URL'sini al
    const currentUrl = new URL(window.location.href);
    
    // 'per_page' parametresini seçilen değere ayarla
    currentUrl.searchParams.set('per_page', amount);
    
    // Gösterim sayısı değiştiğinde her zaman 1. sayfaya dönmek en sağlıklısıdır
    currentUrl.searchParams.set('gen_page', 1); 
    
    // Senin mevcut AJAX fonksiyonun ile history sekmesini güncelle
    loadPage(currentUrl.toString(), 'tab-history', 'tab-history');
}

</script>