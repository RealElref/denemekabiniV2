@extends('layouts.app')
@section('title', 'Ödeme Başarılı')

@push('styles')
<style>
    .result-wrap {
        min-height: calc(100vh - 65px);
        display: flex; align-items: center; justify-content: center;
        padding: 2rem 1.5rem;
    }
    .result-card {
        background: var(--surface);
        border: 1px solid rgba(16,185,129,0.3);
        border-radius: 1.5rem;
        padding: 3rem 2.5rem;
        text-align: center;
        max-width: 480px;
        width: 100%;
    }
    .result-icon {
        width: 5rem; height: 5rem;
        background: rgba(16,185,129,0.1);
        border: 2px solid rgba(16,185,129,0.3);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
    }
    .result-title { font-size: 1.75rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; letter-spacing: -0.03em; }
    .result-sub { color: var(--muted); margin-bottom: 2rem; line-height: 1.6; }
    .credit-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: rgba(124,58,237,0.1);
        border: 1px solid rgba(124,58,237,0.3);
        color: var(--accent-light);
        padding: 0.5rem 1.25rem;
        border-radius: 100px;
        font-size: 1rem; font-weight: 600;
        margin-bottom: 2rem;
    }
    .btn-full { width: 100%; justify-content: center; padding: 0.9rem; border-radius: 0.875rem; font-size: 1rem; display: block; text-align: center; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="result-wrap">
    <div class="result-card">
        <div class="result-icon">✓</div>
        <h1 class="result-title">Ödeme Başarılı!</h1>
        <p class="result-sub">
            {{ $transaction?->package?->name ?? 'Paket' }} satın alındı.<br>
            Krediler hesabına eklendi.
        </p>
        @if($transaction)
        <div class="credit-badge">✦ +{{ $transaction->credit_amount }} kredi eklendi</div>
        @endif
        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-full">
            Panelime Git →
        </a>
    </div>
</div>
@endsection