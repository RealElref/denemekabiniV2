@extends('layouts.app')
@section('title', 'Ödeme Başarısız')

@push('styles')
<style>
    .result-wrap {
        min-height: calc(100vh - 65px);
        display: flex; align-items: center; justify-content: center;
        padding: 2rem 1.5rem;
    }
    .result-card {
        background: var(--surface);
        border: 1px solid rgba(239,68,68,0.3);
        border-radius: 1.5rem;
        padding: 3rem 2.5rem;
        text-align: center;
        max-width: 480px;
        width: 100%;
    }
    .result-icon {
        width: 5rem; height: 5rem;
        background: rgba(239,68,68,0.1);
        border: 2px solid rgba(239,68,68,0.3);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
    }
    .result-title { font-size: 1.75rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; letter-spacing: -0.03em; }
    .result-sub { color: var(--muted); margin-bottom: 2rem; line-height: 1.6; }
    .btn-full { width: 100%; justify-content: center; padding: 0.9rem; border-radius: 0.875rem; font-size: 1rem; display: block; text-align: center; text-decoration: none; margin-bottom: 0.75rem; }
</style>
@endpush

@section('content')
<div class="result-wrap">
    <div class="result-card">
        <div class="result-icon">✕</div>
        <h1 class="result-title">Ödeme Başarısız</h1>
        <p class="result-sub">
            Ödeme işlemi tamamlanamadı.<br>
            Kart bilgilerini kontrol edip tekrar deneyebilirsin.
        </p>
        @if($transaction?->package)
        <a href="{{ route('payment.show', $transaction->package->slug) }}" class="btn btn-primary btn-full">
            Tekrar Dene
        </a>
        @endif
        <a href="{{ route('home') }}" class="btn btn-outline btn-full">
            Ana Sayfaya Dön
        </a>
    </div>
</div>
@endsection