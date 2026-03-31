<div id="tab-credits" class="tab-content">
    <div style="margin-bottom:1.5rem">
        <h3 style="color:var(--text-bright);font-family:'Plus Jakarta Sans',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:0.25rem">{{ __('credits_title') }}</h3>
        <p style="color:var(--text-muted);font-size:0.85rem">{{ __('credits_sub') }} <strong style="color:var(--primary)">{{ $user->credit_balance }} {{ __('credits') }}</strong></p>
    </div>

    <div class="credits-grid">
        @foreach($packages as $package)
        <div class="credit-pkg-card {{ $package->is_featured ? 'featured' : '' }}">
            @if($package->badge_label)
                <div class="credit-pkg-badge">{{ $package->translated_badge }}</div>
            @endif
            <div class="credit-pkg-name">{{ $package->translated_name }}</div>
            <div class="credit-pkg-price">
                {{ number_format($package->price / 100, 0, ',', '.') }}<span>₺</span>
            </div>
            <div class="credit-pkg-credits">✦ {{ $package->credit_amount }} {{ __('credits') }}</div>
            @if($package->features)
            <ul class="credit-pkg-features">
                @foreach($package->translated_features as $feature)
                <li>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
            @endif
            <a href="{{ route('payment.show', $package->slug) }}" class="btn {{ $package->is_featured ? 'btn-primary' : 'btn-outline' }}" style="width:100%;justify-content:center;margin-top:auto">
                {{ __('buy_now') }}
            </a>
        </div>
        @endforeach
    </div>

    <div class="custom-credit-box">
        <h4 style="color:var(--text-bright);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:1rem;margin-bottom:0.4rem">
            🎯 {{ __('custom_credit_title') }}
        </h4>
        <p style="color:var(--text-muted);font-size:0.825rem">{{ __('custom_credit_desc') }}</p>

        <div class="custom-credit-form">
            <div class="credit-input-wrap">
                <label>{{ __('credits') }}</label>
                <div class="credit-controls">
                    <button type="button" class="adj-btn" onclick="adjustCredit(-5)">−</button>
                    <input type="number" class="credit-number-input" id="custom-credit-amount" value="10" min="1" max="1000" oninput="updateCustomPrice()">
                    <button type="button" class="adj-btn" onclick="adjustCredit(5)">+</button>
                </div>
                <div class="quick-select">
                    <button type="button" class="quick-btn" onclick="setCredit(10)">10</button>
                    <button type="button" class="quick-btn" onclick="setCredit(25)">25</button>
                    <button type="button" class="quick-btn" onclick="setCredit(50)">50</button>
                    <button type="button" class="quick-btn" onclick="setCredit(100)">100</button>
                    <button type="button" class="quick-btn" onclick="setCredit(250)">250</button>
                </div>
            </div>
            <div class="custom-price-row">
                <div>
                    <div class="custom-price-label">Total</div>
                    <div class="custom-price-val" id="custom-credit-price">49,00 ₺</div>
                </div>
                <a id="custom-credit-btn" href="{{ route('dashboard.credits') }}?amount=10" class="btn-buy">
                    {{ __('buy_now') }} →
                </a>
            </div>
        </div>
    </div>
</div>