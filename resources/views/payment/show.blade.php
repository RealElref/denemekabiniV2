@extends('layouts.app')

@section('title', 'Ödeme — ' . $package->name)

@push('styles')
<style>
    .payment-wrap {
        max-width: 960px;
        margin: 0 auto;
        padding: 3rem 1.5rem;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }
    @media (max-width: 768px) {
        .payment-wrap { grid-template-columns: 1fr; }
    }
    .pay-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 1.5rem;
        padding: 2rem;
    }
    .pay-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.03em;
        margin-bottom: 0.5rem;
    }
    .pay-sub { color: var(--muted); font-size: 0.875rem; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.25rem; }
    label {
        display: block;
        font-size: 0.85rem;
        font-weight: 500;
        color: #D1D5DB;
        margin-bottom: 0.4rem;
    }
    input[type="text"],
    input[type="email"],
    input[type="tel"] {
        width: 100%;
        background: var(--surface2);
        border: 1px solid var(--border);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        transition: border-color 0.2s;
        outline: none;
    }
    input:focus { border-color: var(--accent); }
    .divider { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0; }
    .secure-badge {
        display: flex; align-items: center; gap: 0.5rem;
        color: var(--muted); font-size: 0.8rem;
        margin-top: 1rem; justify-content: center;
    }
    .summary-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 1.5rem;
        padding: 2rem;
        position: sticky;
        top: 80px;
    }
    .summary-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 1.5rem; }
    .pkg-summary {
        background: rgba(124,58,237,0.08);
        border: 1px solid rgba(124,58,237,0.2);
        border-radius: 1rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .pkg-summary-name { font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 0.25rem; }
    .pkg-summary-desc { font-size: 0.85rem; color: var(--muted); margin-bottom: 1rem; }
    .pkg-summary-credits { display: flex; align-items: center; gap: 0.5rem; color: var(--accent-light); font-size: 0.9rem; font-weight: 600; }
    .summary-row {
        display: flex; justify-content: space-between;
        font-size: 0.875rem; padding: 0.5rem 0;
        border-bottom: 1px solid var(--border); color: var(--muted);
    }
    .summary-row:last-of-type { border-bottom: none; }
    .summary-row.total {
        font-size: 1.1rem; font-weight: 700; color: #fff;
        padding-top: 1rem; margin-top: 0.5rem;
        border-top: 1px solid var(--border);
    }
    .btn-pay {
        width: 100%; justify-content: center; padding: 0.9rem;
        font-size: 1rem; border-radius: 0.875rem; margin-top: 1.5rem;
        font-weight: 600; border: none; cursor: pointer;
    }
    .features-list { list-style: none; margin-top: 1.5rem; }
    .features-list li {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.85rem; color: #D1D5DB; padding: 0.35rem 0;
    }
    .check { color: #10B981; }
</style>
@endpush

@section('content')
<div style="max-width:960px;margin:2rem auto;padding:0 1.5rem">
    <a href="{{ route('home') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem">← Geri Dön</a>
</div>

<div class="payment-wrap">
    <div>
        <div class="pay-card">
            <h1 class="pay-title">Ödeme Bilgileri</h1>
            <p class="pay-sub">Bilgilerini onayla, güvenli ödeme sayfasına yönlendirileceksin.</p>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('payment.process', $package->slug) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Ad Soyad</label>
                    <input type="text" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="form-group">
                    <label>E-posta</label>
                    <input type="email" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="form-group">
                    <label>Telefon</label>
                    <input type="tel" name="phone" value="{{ $user->phone }}" placeholder="05xx xxx xx xx">
                </div>
                <hr class="divider">
                <p style="font-size:0.85rem;color:var(--muted);margin-bottom:1rem">
                    Ödeme butonuna tıkladığında güvenli Polarsh ödeme sayfasına yönlendirileceksin.
                </p>
                <button type="submit" class="btn btn-primary btn-pay">
                    🔒 Güvenli Ödemeye Geç — {{ number_format($package->price / 100, 2) }} ₺
                </button>
                <div class="secure-badge">
                    🔒 256-bit SSL şifrelemesi ile güvenli ödeme
                </div>
            </form>
        </div>
    </div>

    <div class="summary-card">
        <div class="summary-title">Sipariş Özeti</div>
        <div class="pkg-summary">
            @if($package->badge_label)
                <div style="margin-bottom:0.5rem">
                    <span style="background:var(--accent);color:#fff;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:100px;text-transform:uppercase">
                        {{ $package->badge_label }}
                    </span>
                </div>
            @endif
            <div class="pkg-summary-name">{{ $package->name }} Paketi</div>
            <div class="pkg-summary-desc">{{ $package->description }}</div>
            <div class="pkg-summary-credits">✦ {{ $package->credit_amount }} Deneme Kredisi</div>
        </div>

        @if($package->features)
        <ul class="features-list">
            @foreach($package->features as $feature)
            <li>
                <span class="check">✓</span>
                {{ is_array($feature) ? $feature['item'] : $feature }}
            </li>
            @endforeach
        </ul>
        @endif

        <hr class="divider">

        <div class="summary-row">
            <span>Paket Fiyatı</span>
            <span>{{ number_format($package->price / 100, 2) }} ₺</span>
        </div>
        <div class="summary-row">
            <span>KDV</span>
            <span>Dahil</span>
        </div>
        <div class="summary-row total">
            <span>Toplam</span>
            <span style="color:var(--accent-light)">{{ number_format($package->price / 100, 2) }} ₺</span>
        </div>

        <div style="margin-top:1.5rem;padding:1rem;background:rgba(16,185,129,0.05);border:1px solid rgba(16,185,129,0.2);border-radius:0.75rem">
            <div style="font-size:0.8rem;color:#6EE7B7;font-weight:600;margin-bottom:0.25rem">✓ Mevcut Kredin</div>
            <div style="font-size:0.875rem;color:var(--muted)">
                Şu an: <strong style="color:#fff">{{ $user->credit_balance }} kredi</strong><br>
                Ödeme sonrası: <strong style="color:var(--accent-light)">{{ $user->credit_balance + $package->credit_amount }} kredi</strong>
            </div>
        </div>
    </div>
</div>
@endsection