<header class="app-header">
    <div>
        <h1 class="panel-title">{{ __('dashboard_title', ['name' => $user->name]) }}</h1>
        <p class="panel-sub">{{ __('dashboard_sub') }}</p>
    </div>
    <button onclick="openTryOn()" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 5v14M5 12h14"/></svg>
        {{ __('new_try') }}
    </button>
</header>

<div class="promo-bar">
    <span class="promo-text">
        <strong style="color:var(--primary)">🎁 {{ __('earn_credit') }}:</strong>
        {{ __('earn_credit_desc') }}
    </span>
    <span class="promo-link" onclick="copyRef(this)" title="{{ __('copy_link') }}">
        {{ url('/kayit?ref=' . $user->referral_code) }}
    </span>
</div>