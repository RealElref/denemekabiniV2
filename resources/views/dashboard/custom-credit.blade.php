@extends('layouts.app')

@section('title', 'Kredi Satın Al')

@push('styles')
<style>
    * { box-sizing: border-box; }
    html, body { max-width: 100vw; overflow-x: hidden; }

    :root {
        --bg-main: #020617;
        --primary: #818CF8;
        --primary-dark: #6366F1;
        --primary-glow: rgba(129, 140, 248, 0.25);
        --text-bright: #FFFFFF;
        --text-main: #F8FAFC;
        --text-muted: #94A3B8;
        --glass-bg: rgba(30, 41, 59, 0.4);
        --glass-border: rgba(255, 255, 255, 0.08);
        --radius-md: 12px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-main);
        color: var(--text-main);
        background-image: linear-gradient(var(--glass-border) 1px, transparent 1px),
                          linear-gradient(90deg, var(--glass-border) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .wrap {
        max-width: 960px;
        margin: 0 auto;
        padding: 2.5rem 5% 4rem;
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 768px) {
        .wrap { grid-template-columns: 1fr; }
    }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--text-muted); text-decoration: none; font-size: 0.875rem;
        margin-bottom: 1.5rem; transition: var(--transition);
        max-width: 1200px; margin-inline: auto; padding: 0 5%;
        display: flex;
    }
    .back-link:hover { color: var(--text-bright); }

    .card {
        background: var(--glass-bg); border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg); padding: 2rem;
        backdrop-filter: blur(12px);
    }

    .card-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.3rem; font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #A5B4FC 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        margin-bottom: 0.4rem;
    }
    .card-sub { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 2rem; }

    /* Kredi seçici */
    .credit-selector { margin-bottom: 2rem; }
    .credit-selector label {
        display: block; font-size: 0.8rem; font-weight: 600;
        color: var(--text-muted); text-transform: uppercase;
        letter-spacing: 0.05em; margin-bottom: 0.75rem;
    }
    .credit-controls {
        display: flex; align-items: center; gap: 1rem;
    }
    .adj-btn {
        width: 44px; height: 44px;
        background: var(--glass-bg); border: 1px solid var(--glass-border);
        border-radius: 12px; color: var(--text-bright); font-size: 1.4rem;
        cursor: pointer; transition: var(--transition);
        display: flex; align-items: center; justify-content: center;
        font-family: monospace; flex-shrink: 0;
    }
    .adj-btn:hover { border-color: var(--primary); color: var(--primary); }

    .credit-input {
        flex: 1; text-align: center;
        background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border);
        border-radius: 12px; padding: 0.75rem;
        color: var(--text-bright); font-size: 1.5rem; font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: var(--transition); outline: none;
    }
    .credit-input:focus { border-color: var(--primary); }

    /* Hızlı seçim */
    .quick-select {
        display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;
    }
    .quick-btn {
        padding: 0.35rem 0.75rem;
        background: rgba(129,140,248,0.08);
        border: 1px solid rgba(129,140,248,0.2);
        border-radius: 99px; color: var(--primary);
        font-size: 0.8rem; font-weight: 600; cursor: pointer;
        transition: var(--transition); font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .quick-btn:hover { background: rgba(129,140,248,0.2); }

    /* Form */
    .form-group { margin-bottom: 1.25rem; }
    .form-group label {
        display: block; font-size: 0.8rem; font-weight: 600;
        color: var(--text-muted); text-transform: uppercase;
        letter-spacing: 0.05em; margin-bottom: 0.4rem;
    }
    .form-input {
        width: 100%; background: rgba(0,0,0,0.3);
        border: 1px solid var(--glass-border); border-radius: 10px;
        padding: 0.75rem 1rem; color: var(--text-bright);
        font-family: 'Inter', sans-serif; font-size: 0.95rem;
        transition: var(--transition); outline: none;
    }
    .form-input:focus { border-color: var(--primary); }

    /* Özet kartı */
    .summary-card {
        background: var(--glass-bg); border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg); padding: 2rem;
        backdrop-filter: blur(12px); position: sticky; top: 80px;
    }
    .summary-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1rem; font-weight: 700; color: var(--text-bright);
        margin-bottom: 1.5rem;
    }
    .summary-row {
        display: flex; justify-content: space-between;
        font-size: 0.875rem; padding: 0.6rem 0;
        border-bottom: 1px solid var(--glass-border); color: var(--text-muted);
    }
    .summary-row:last-of-type { border-bottom: none; }
    .summary-row.total {
        font-size: 1.2rem; font-weight: 700; color: var(--text-bright);
        padding-top: 1rem; margin-top: 0.5rem;
        border-top: 1px solid var(--glass-border);
    }
    .summary-credit-box {
        margin-top: 1.25rem; padding: 1rem;
        background: rgba(129,140,248,0.08);
        border: 1px solid rgba(129,140,248,0.2);
        border-radius: 10px;
    }
    .btn-pay {
        width: 100%; justify-content: center; padding: 0.9rem;
        font-size: 1rem; border-radius: 12px; margin-top: 1.25rem;
        font-weight: 700; border: none; cursor: pointer;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff; box-shadow: 0 4px 20px var(--primary-glow);
        transition: var(--transition); display: flex; align-items: center;
        gap: 0.5rem; text-decoration: none;
    }
    .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 8px 30px var(--primary-glow); }
    .secure-note {
        text-align: center; font-size: 0.75rem; color: var(--text-muted);
        margin-top: 0.75rem; display: flex; align-items: center;
        justify-content: center; gap: 0.4rem;
    }

    /* Light mode */
    html.light body { background-color: #E8EEFF; }
    html.light .card { background: rgba(255,255,255,0.9); border-color: rgba(99,102,241,0.15); }
    html.light .summary-card { background: rgba(255,255,255,0.9); border-color: rgba(99,102,241,0.15); }
    html.light .credit-input { background: rgba(255,255,255,0.8); color: #0F172A; }
    html.light .form-input { background: rgba(255,255,255,0.8); color: #0F172A; }
    html.light .card-title { background: linear-gradient(135deg, #0F172A 0%, #6366F1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    html.light body { background-image: linear-gradient(rgba(99,102,241,0.06) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,0.06) 1px,transparent 1px); }
</style>
@endpush

@section('content')

<a href="{{ route('dashboard') }}" class="back-link">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Panele Dön
</a>

<div class="wrap">

    {{-- Sol: Form --}}
    <div>
        <div class="card">
            <h1 class="card-title">Kredi Satın Al</h1>
            <p class="card-sub">Mevcut bakiyeniz: <strong style="color:var(--primary)">{{ $user->credit_balance }} kredi</strong></p>

            @if(session('error'))
                <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#FCA5A5;padding:0.75rem 1rem;border-radius:10px;margin-bottom:1rem;font-size:0.875rem">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('dashboard.credits.process') }}" method="POST" id="credit-form">
                @csrf
                <input type="hidden" name="amount" id="form-amount" value="{{ $amount }}">

                <div class="credit-selector">
                    <label>Kredi Miktarı</label>
                    <div class="credit-controls">
                        <button type="button" class="adj-btn" onclick="adjustAmt(-5)">−</button>
                        <input type="number" class="credit-input" id="credit-display"
                            value="{{ $amount }}" min="1" max="1000"
                            oninput="syncAmount(this.value)">
                        <button type="button" class="adj-btn" onclick="adjustAmt(5)">+</button>
                    </div>
                    <div class="quick-select">
                        <button type="button" class="quick-btn" onclick="setAmount(10)">10 kr</button>
                        <button type="button" class="quick-btn" onclick="setAmount(25)">25 kr</button>
                        <button type="button" class="quick-btn" onclick="setAmount(50)">50 kr</button>
                        <button type="button" class="quick-btn" onclick="setAmount(100)">100 kr</button>
                        <button type="button" class="quick-btn" onclick="setAmount(250)">250 kr</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ad Soyad</label>
                    <input type="text" class="form-input" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="form-group">
                    <label>E-posta</label>
                    <input type="email" class="form-input" name="email" value="{{ $user->email }}" required>
                </div>

                <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:1rem">
                    Ödeme butonuna tıkladığında güvenli Polar ödeme sayfasına yönlendirileceksin.
                </p>

                <button type="submit" class="btn-pay">
                    🔒 Güvenli Ödemeye Geç —
                    <span id="btn-price">{{ number_format($price / 100, 2, ',', '.') }} ₺</span>
                </button>
                <div class="secure-note">🔒 256-bit SSL ile korunmaktadır</div>
            </form>
        </div>
    </div>

    {{-- Sağ: Özet --}}
    <div class="summary-card">
        <div class="summary-title">Sipariş Özeti</div>

        <div class="summary-row">
            <span>Kredi Miktarı</span>
            <span id="sum-amount">{{ $amount }} kredi</span>
        </div>
        <div class="summary-row">
            <span>Birim Fiyat</span>
            <span>4,90 ₺ / kredi</span>
        </div>
        <div class="summary-row">
            <span>KDV</span>
            <span>Dahil</span>
        </div>
        <div class="summary-row total">
            <span>Toplam</span>
            <span id="sum-total" style="color:var(--primary)">{{ number_format($price / 100, 2, ',', '.') }} ₺</span>
        </div>

        <div class="summary-credit-box">
            <div style="font-size:0.75rem;color:var(--primary);font-weight:600;margin-bottom:0.25rem">✦ Ödeme Sonrası Bakiye</div>
            <div style="font-size:0.875rem;color:var(--text-muted)">
                Şu an: <strong style="color:var(--text-bright)">{{ $user->credit_balance }} kredi</strong><br>
                Sonra: <strong style="color:var(--primary)" id="sum-after">{{ $user->credit_balance + $amount }} kredi</strong>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const unitPrice = 4.9;
    const currentBalance = {{ $user->credit_balance }};

    function setAmount(val) {
        val = Math.max(1, Math.min(1000, val));
        document.getElementById('credit-display').value = val;
        document.getElementById('form-amount').value = val;
        updateSummary(val);
    }

    function adjustAmt(delta) {
        const current = parseInt(document.getElementById('credit-display').value) || 1;
        setAmount(current + delta);
    }

    function syncAmount(val) {
        val = Math.max(1, Math.min(1000, parseInt(val) || 1));
        document.getElementById('form-amount').value = val;
        updateSummary(val);
    }

    function updateSummary(amount) {
        const total = (amount * unitPrice).toFixed(2).replace('.', ',');
        document.getElementById('sum-amount').innerText = amount + ' kredi';
        document.getElementById('sum-total').innerText = total + ' ₺';
        document.getElementById('sum-after').innerText = (currentBalance + amount) + ' kredi';
        document.getElementById('btn-price').innerText = total + ' ₺';
    }
</script>
@endpush