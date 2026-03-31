<div class="stats-row">
    <div class="stat-card">
        <div class="stat-label">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
            {{ __('balance') }}
        </div>
        <div class="stat-value accent">{{ $user->credit_balance }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            {{ __('production') }}
        </div>
        <div class="stat-value">{{ $user->generations()->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
            {{ __('domain') }}
        </div>
        <div class="stat-value">{{ $user->domains()->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            {{ __('invite') }}
        </div>
        <div class="stat-value">{{ $user->referrals()->count() }}</div>
    </div>
</div>