@extends('layouts.app')

@section('title', __('login_btn'))

@push('styles')
<style>
    .auth-wrap {
        min-height: calc(100vh - 64px);
        display: flex; align-items: center; justify-content: center;
        padding: 2rem 1rem;
    }
    .auth-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 1.5rem;
        padding: 2.5rem;
        width: 100%;
        max-width: 440px;
        backdrop-filter: blur(12px);
    }
    .auth-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.75rem; font-weight: 800;
        background: linear-gradient(135deg, #FFFFFF 0%, #A5B4FC 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem; letter-spacing: -0.03em;
    }
    html.light .auth-title {
        background: linear-gradient(135deg, #0F172A 0%, #6366F1 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .auth-sub { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-group label {
        display: block; font-size: 0.85rem; font-weight: 500;
        color: var(--text-main); margin-bottom: 0.4rem;
    }
    .form-input {
        width: 100%;
        background: rgba(0,0,0,0.2);
        border: 1px solid var(--glass-border);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--text-bright);
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        transition: border-color 0.2s;
        outline: none;
    }
    html.light .form-input { background: rgba(255,255,255,0.8); color: #0F172A; }
    .form-input:focus { border-color: var(--primary); }
    .form-input.is-error { border-color: #EF4444; }
    .field-error { color: #FCA5A5; font-size: 0.8rem; margin-top: 0.3rem; }
    .remember-row {
        display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;
    }
    .remember-row input[type="checkbox"] { width: auto; accent-color: var(--primary); }
    .remember-row label { margin: 0; font-size: 0.85rem; color: var(--text-muted); }
    .btn-full {
        width: 100%; justify-content: center; padding: 0.8rem;
        font-size: 1rem; border-radius: 0.75rem; border: none; cursor: pointer;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;
        transition: all 0.3s; box-shadow: 0 4px 15px var(--primary-glow);
        display: flex; align-items: center; justify-content: center;
    }
    .btn-full:hover { transform: translateY(-2px); box-shadow: 0 8px 25px var(--primary-glow); }
    .auth-footer { text-align: center; margin-top: 1.5rem; color: var(--text-muted); font-size: 0.875rem; }
    .auth-footer a { color: var(--primary); text-decoration: none; font-weight: 500; }
    .auth-footer a:hover { text-decoration: underline; }
    .alert-danger {
        background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);
        color: #FCA5A5; padding: 0.75rem 1rem; border-radius: 0.75rem;
        margin-bottom: 1.25rem; font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-title">{{ __('login_title') }}</h1>
        <p class="auth-sub">{{ __('login_sub') }}</p>

        @if($errors->any())
            <div class="alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('email') }}</label>
                <input type="email" id="email" name="email"
                    value="{{ old('email') }}"
                    placeholder="example@mail.com"
                    class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                    required autofocus>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('password') }}</label>
                <input type="password" id="password" name="password"
                    placeholder="••••••••"
                    class="form-input"
                    required>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">{{ __('remember_me') }}</label>
            </div>

            <button type="submit" class="btn-full">{{ __('login_btn') }}</button>
        </form>

        <div class="auth-footer">
            {{ __('no_account') }} <a href="{{ route('register') }}">{{ __('register_link') }}</a>
        </div>
    </div>
</div>
@endsection