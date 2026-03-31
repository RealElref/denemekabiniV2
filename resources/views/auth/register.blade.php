@extends('layouts.app')

@section('title', __('register_title'))

@push('styles')
<style>
    /* Premium Tipografi */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap');

    /* SIFIR SCROLL & TAŞMA ENGELLEYİCİ ANA AYARLAR */
    * { 
        box-sizing: border-box; 
        min-width: 0; 
    }
    
    html, body {
        width: 100%;
        height: 100vh; 
        height: 100dvh; /* Mobil tarayıcıların adres çubuğu hesabı (Çok önemli!) */
        max-width: 100vw;
        overflow: hidden !important; /* Sayfanın dışarıdan kaymasını kesin olarak kilitler */
        margin: 0; 
        padding: 0;
    }

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
        --glass-border-hover: rgba(255, 255, 255, 0.15);
        --radius-md: 12px;
        --radius-lg: 20px;
        --transition: all 0.3s ease;
    }

    html.light {
        --bg-main: #E8EEFF;
        --primary: #6366F1;
        --primary-dark: #4F46E5;
        --text-bright: #0F172A;
        --text-main: #1E293B;
        --text-muted: #475569;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(99, 102, 241, 0.15);
        --glass-border-hover: rgba(99, 102, 241, 0.35);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-main);
        color: var(--text-main);
        background-image: linear-gradient(var(--glass-border) 1px, transparent 1px),
                          linear-gradient(90deg, var(--glass-border) 1px, transparent 1px);
        background-size: 40px 40px;
        background-position: center top;
        transition: background-color 0.4s;
    }

    /* Form Kapsayıcısı (Ekranın tam ortasına kilitler) */
    .auth-wrap {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    /* Kimlik Doğrulama Kartı */
    .auth-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 2.5rem 2rem;
        width: 100%;
        max-width: 440px;
        backdrop-filter: blur(12px);
        
        /* KLAVYE AÇILINCA SADECE KARTIN İÇİ KAYAR */
        max-height: 100%;
        overflow-y: auto;
        overflow-x: hidden; /* Kartın sağa sola kaymasını engeller */
        -webkit-overflow-scrolling: touch;
    }
    
    /* Kart içi gizli/şık scrollbar */
    .auth-card::-webkit-scrollbar { width: 4px; }
    .auth-card::-webkit-scrollbar-track { background: transparent; }
    .auth-card::-webkit-scrollbar-thumb { background: rgba(129,140,248,0.3); border-radius: 10px; }
    html.light .auth-card::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.2); }

    .welcome-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3);
        color: #34D399; font-size: 0.75rem; font-weight: 600;
        padding: 0.25rem 0.75rem; border-radius: 100px; margin-bottom: 1rem;
        text-transform: uppercase; letter-spacing: 0.05em;
    }

    .auth-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(1.5rem, 4vw, 1.8rem); font-weight: 800;
        background: linear-gradient(135deg, #FFFFFF 0%, #A5B4FC 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        margin-bottom: 0.25rem; letter-spacing: -0.02em; line-height: 1.2;
    }
    html.light .auth-title {
        background: linear-gradient(135deg, #0F172A 0%, #6366F1 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .auth-sub { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.75rem; font-weight: 300; }

    /* Kompakt Form Yapısı */
    .form-group { margin-bottom: 1rem; width: 100%; }
    .form-group label {
        display: block; font-size: 0.8rem; font-weight: 600;
        color: var(--text-muted); margin-bottom: 0.35rem;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    
    .form-input {
        width: 100%; background: rgba(0,0,0,0.2);
        border: 1px solid var(--glass-border); border-radius: var(--radius-md);
        padding: 0.7rem 1rem; color: var(--text-bright);
        font-family: 'Inter', sans-serif; font-size: 0.9rem;
        transition: var(--transition); outline: none;
    }
    html.light .form-input { background: rgba(255,255,255,0.8); color: #0F172A; }
    .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.1); }
    .form-input.is-error { border-color: #EF4444; }
    .field-error { color: #FCA5A5; font-size: 0.75rem; margin-top: 0.3rem; }

    /* Bildirim Kutuları */
    .alert-info, .alert-danger {
        padding: 0.6rem 1rem; border-radius: 0.5rem; font-size: 0.8rem; margin-bottom: 1rem;
        display: flex; align-items: center; gap: 0.5rem; font-weight: 500;
    }
    .alert-info { background: rgba(129,140,248,0.1); border: 1px solid rgba(129,140,248,0.3); color: var(--primary); }
    .alert-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #FCA5A5; }

    /* Aksiyon Butonu */
    .btn-full {
        width: 100%; justify-content: center; padding: 0.8rem; margin-top: 0.5rem;
        font-size: 0.95rem; border-radius: var(--radius-md); border: none; cursor: pointer;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;
        transition: var(--transition); box-shadow: 0 4px 15px var(--primary-glow);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .btn-full:hover { transform: translateY(-2px); box-shadow: 0 8px 25px var(--primary-glow); }

    /* Alt Metinler */
    .terms { font-size: 0.75rem; color: var(--text-muted); text-align: center; margin-top: 1rem; line-height: 1.5; }
    .terms a { color: var(--primary); text-decoration: none; font-weight: 500; }
    .terms a:hover { text-decoration: underline; }
    
    .auth-footer { text-align: center; margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid var(--glass-border); color: var(--text-muted); font-size: 0.85rem; }
    .auth-footer a { color: var(--primary); text-decoration: none; font-weight: 600; margin-left: 0.2rem; }
    .auth-footer a:hover { text-decoration: underline; }

    /* Mobilde Klavye Açılınca Daralan Ekranı Kurtarmak İçin */
    @media (max-width: 480px) {
        .auth-wrap { padding: 0.5rem; }
        .auth-card { padding: 1.5rem 1.25rem; border-radius: 1rem; }
        .welcome-badge { margin-bottom: 0.75rem; }
        .auth-title { font-size: 1.4rem; }
        .auth-sub { margin-bottom: 1.25rem; }
        .form-group { margin-bottom: 0.8rem; }
        .form-input { padding: 0.6rem 0.8rem; }
        .btn-full { padding: 0.75rem; font-size: 0.9rem; }
    }
    
    /* Yükseklik çok kısıtlıysa (Yatay telefon, açık klavye vs.) logoyu falan gizle */
    @media (max-height: 600px) {
        .auth-card { padding: 1.25rem; }
        .welcome-badge { display: none; }
        .auth-sub { margin-bottom: 1rem; }
        .form-group { margin-bottom: 0.6rem; }
    }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">

        <div class="welcome-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
            {{ __('register_badge') }}
        </div>
        <h1 class="auth-title">{{ __('register_title') }}</h1>
        <p class="auth-sub">{{ __('register_sub') }}</p>

        @if(session('info'))
            <div class="alert-info">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-danger">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" autocomplete="off">
            @csrf

            @if(request('ref'))
                <input type="hidden" name="referral_code" value="{{ request('ref') }}">
            @endif

        @if(session('selected_package'))
    <input type="hidden" name="selected_package" value="{{ session('selected_package') }}">
@endif

            <div class="form-group">
                <label for="name">{{ __('full_name') }}</label>
                <input type="text" id="name" name="name"
                    value="{{ old('name') }}"
                    placeholder="{{ __('full_name_placeholder') }}"
                    class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                    required autofocus>
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('email') }}</label>
                <input type="email" id="email" name="email"
                    value="{{ old('email') }}"
                    placeholder="{{ __('email_placeholder') }}"
                    class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                    required>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('password') }}</label>
                <input type="password" id="password" name="password"
                    placeholder="{{ __('password_min') }}"
                    class="form-input"
                    required>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('password_confirm') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="{{ __('password_confirm_placeholder') }}"
                    class="form-input"
                    required>
            </div>

            <button type="submit" class="btn-full">
                {{ __('register_btn') }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>

            <p class="terms">
                {{ __('terms_text') }} <a href="#">{{ __('terms_link') }}</a> {{ __('terms_accept') }}
            </p>
        </form>

        <div class="auth-footer">
            {{ __('have_account') }} <a href="{{ route('login') }}">{{ __('login_link') }}</a>
        </div>

    </div>
</div>
@endsection