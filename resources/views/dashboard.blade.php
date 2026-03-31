@extends('layouts.app')

@section('title', __('nav_panel'))

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
        transition: background-color 0.4s;
    }

    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); border-radius: 10px; }
    ::-webkit-scrollbar-thumb { background: rgba(129,140,248,0.3); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
        text-decoration: none; font-weight: 600; font-size: 0.85rem;
        padding: 0.6rem 1.25rem; border-radius: 99px;
        transition: var(--transition); cursor: pointer; border: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #fff; box-shadow: 0 4px 15px var(--primary-glow);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px var(--primary-glow); }
    .btn-outline {
        background: var(--glass-bg); color: var(--text-bright);
        border: 1px solid var(--glass-border);
    }
    .btn-outline:hover { border-color: var(--primary); }

    .panel-wrap {
        max-width: 1200px; margin: 0 auto;
        padding: 2rem 5% 4rem; width: 100%;
    }

    .app-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;
    }
    .panel-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(1.4rem, 4vw, 2rem); font-weight: 800;
        background: linear-gradient(135deg, #FFFFFF 0%, #A5B4FC 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        letter-spacing: -0.02em; margin-bottom: 0.2rem;
    }
    html.light .panel-title {
        background: linear-gradient(135deg, #0F172A 0%, #6366F1 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .panel-sub { color: var(--text-muted); font-size: 0.875rem; font-weight: 300; }

    .promo-bar {
        background: linear-gradient(90deg, rgba(99,102,241,0.1) 0%, rgba(2,6,23,0.4) 100%);
        border: 1px solid rgba(99,102,241,0.3); border-radius: var(--radius-md);
        padding: 0.75rem 1.25rem;
        display: flex; align-items: center; justify-content: space-between;
        font-size: 0.875rem; color: var(--text-bright);
        backdrop-filter: blur(10px); margin-bottom: 1.5rem;
        gap: 1rem; flex-wrap: wrap;
    }
    .promo-text { flex: 1; min-width: 200px; }
    .promo-link {
        color: var(--primary); font-family: monospace; font-weight: 600; cursor: pointer;
        padding: 0.35rem 0.75rem; background: rgba(0,0,0,0.3); border-radius: 8px;
        border: 1px solid rgba(129,140,248,0.2); transition: var(--transition);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        max-width: 280px; font-size: 0.8rem;
    }
    .promo-link:hover { background: rgba(129,140,248,0.2); }
    html.light .promo-bar {
        background: linear-gradient(90deg, rgba(99,102,241,0.08) 0%, rgba(240,244,255,0.6) 100%);
        border-color: rgba(99,102,241,0.25);
    }
    html.light .promo-link { background: rgba(255,255,255,0.8); }

    .stats-row {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 1rem; margin-bottom: 1.5rem;
    }
    .stat-card {
        background: var(--glass-bg); border: 1px solid var(--glass-border);
        border-radius: var(--radius-md); padding: 1.25rem;
        backdrop-filter: blur(12px); transition: var(--transition);
    }
    .stat-card:hover { border-color: var(--glass-border-hover); }
    .stat-label {
        font-size: 0.75rem; color: var(--text-muted); font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em;
        display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.5rem;
    }
    .stat-value {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2rem; font-weight: 800; color: var(--text-bright); line-height: 1;
    }
    .stat-value.accent { color: var(--primary); text-shadow: 0 0 15px var(--primary-glow); }

    .tab-section {
        background: var(--glass-bg); border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg); backdrop-filter: blur(12px); overflow: hidden;
    }
    .tab-nav-wrapper {
        background: rgba(2,6,23,0.3); border-bottom: 1px solid var(--glass-border);
        overflow-x: auto; -webkit-overflow-scrolling: touch;
    }
    html.light .tab-nav-wrapper { background: rgba(240,244,255,0.5); }
    .tab-nav-wrapper::-webkit-scrollbar { display: none; }
    .tab-nav {
        display: flex; gap: 0.5rem; padding: 1rem 1.5rem;
        width: max-content; min-width: 100%;
    }
    .tab-btn {
        background: transparent; color: var(--text-muted);
        border: 1px solid transparent; padding: 0.5rem 1.25rem;
        border-radius: 99px; font-weight: 600; font-size: 0.875rem;
        cursor: pointer; transition: var(--transition);
        font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap;
    }
    .tab-btn:hover { color: var(--text-bright); background: rgba(255,255,255,0.05); }
    .tab-btn.active {
        background: rgba(129,140,248,0.15); color: var(--primary);
        border-color: rgba(129,140,248,0.3); box-shadow: 0 0 15px var(--primary-glow);
    }

    .tab-content { display: none; padding: 1.5rem; }
    .tab-content.active { display: block; animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }

    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 480px; }
    .data-table th {
        text-align: left; font-size: 0.75rem; font-weight: 600;
        color: var(--text-muted); text-transform: uppercase;
        padding: 0.5rem 0.75rem 0.75rem; border-bottom: 1px solid var(--glass-border);
        white-space: nowrap;
    }
    .data-table td {
        padding: 0.875rem 0.75rem; font-size: 0.875rem;
        color: var(--text-main); border-bottom: 1px solid rgba(255,255,255,0.03);
        white-space: nowrap;
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover td { background: rgba(255,255,255,0.02); }

    .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.6rem; border-radius: 99px; font-size: 0.7rem; font-weight: 600; }
    .badge-success { background: rgba(16,185,129,0.15); color: #34D399; }
    .badge-warning { background: rgba(245,158,11,0.15); color: #FBBF24; }
    .badge-danger  { background: rgba(239,68,68,0.15); color: #F87171; }
    .badge-gray    { background: rgba(107,114,128,0.15); color: #9CA3AF; }

    .generations-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 1rem;
    }
    .gen-card {
        background: rgba(2,6,23,0.5); border: 1px solid var(--glass-border);
        border-radius: var(--radius-md); aspect-ratio: 3/4;
        display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden;
    }
    .gen-card img { width: 100%; height: 100%; object-fit: cover; }
    .gen-card::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 40%);
        pointer-events: none;
    }
    .gen-status { position: absolute; bottom: 0.5rem; left: 50%; transform: translateX(-50%); z-index: 2; }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-muted); }
    .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; opacity: 0.5; }
    .empty-state p { font-size: 0.9rem; }

    /* Pagination */
    .pagination-wrap {
        margin-top: 1.25rem;
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; flex-wrap: wrap;
    }
    .pagination-info { font-size: 0.8rem; color: var(--text-muted); }
    .pagination-btns { display: flex; gap: 0.5rem; }
    .page-btn {
        padding: 0.4rem 0.9rem; border-radius: 99px;
        border: 1px solid var(--glass-border); color: var(--text-bright);
        font-size: 0.8rem; text-decoration: none; background: var(--glass-bg);
        transition: var(--transition); font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
    }
    .page-btn:hover { border-color: var(--primary); color: var(--primary); }
    .page-btn.disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }

    /* Credits Tab */
    .credits-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;
    }
    .credit-pkg-card {
        background: rgba(2,6,23,0.4); border: 1px solid var(--glass-border);
        border-radius: 16px; padding: 1.5rem;
        display: flex; flex-direction: column; gap: 0.5rem;
        position: relative; transition: var(--transition);
    }
    html.light .credit-pkg-card { background: rgba(255,255,255,0.8); }
    .credit-pkg-card:hover { border-color: var(--glass-border-hover); transform: translateY(-3px); }
    .credit-pkg-card.featured { border-color: var(--primary); background: rgba(129,140,248,0.05); }
    .credit-pkg-badge {
        position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.05em; padding: 0.25rem 0.75rem; border-radius: 99px; white-space: nowrap;
    }
    .credit-pkg-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 700; color: var(--text-bright); }
    .credit-pkg-price {
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.75rem; font-weight: 800;
        color: var(--text-bright); display: flex; align-items: flex-start; line-height: 1;
    }
    .credit-pkg-price span { font-size: 0.9rem; font-weight: 500; color: var(--text-muted); margin-top: 0.3rem; margin-left: 0.1rem; }
    .credit-pkg-credits { font-size: 0.8rem; font-weight: 600; color: var(--primary); }
    .credit-pkg-features { list-style: none; margin: 0.25rem 0; flex: 1; }
    .credit-pkg-features li {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.78rem; color: var(--text-muted); padding: 0.2rem 0;
    }
    .credit-pkg-features li svg { color: var(--primary); flex-shrink: 0; }

    .custom-credit-box {
        margin-top: 1.5rem;
        background: rgba(129,140,248,0.05);
        border: 1px solid rgba(129,140,248,0.2);
        border-radius: 16px; padding: 1.5rem;
    }
    html.light .custom-credit-box { background: rgba(99,102,241,0.05); }
    .custom-credit-form { display: flex; align-items: flex-end; gap: 1.5rem; flex-wrap: wrap; margin-top: 1rem; }
    .credit-input-wrap { flex: 1; min-width: 200px; }
    .credit-input-wrap label {
        display: block; font-size: 0.75rem; font-weight: 600;
        color: var(--text-muted); text-transform: uppercase;
        letter-spacing: 0.05em; margin-bottom: 0.5rem;
    }
    .credit-controls { display: flex; align-items: center; gap: 0.75rem; }
    .adj-btn {
        width: 38px; height: 38px;
        background: var(--glass-bg); border: 1px solid var(--glass-border);
        border-radius: 10px; color: var(--text-bright); font-size: 1.2rem;
        cursor: pointer; transition: var(--transition);
        display: flex; align-items: center; justify-content: center;
    }
    .adj-btn:hover { border-color: var(--primary); color: var(--primary); }
    .credit-number-input {
        width: 80px; text-align: center;
        background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border);
        border-radius: 10px; padding: 0.5rem;
        color: var(--text-bright); font-size: 1.2rem; font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif; outline: none;
        transition: var(--transition);
    }
    html.light .credit-number-input { background: rgba(255,255,255,0.8); color: #0F172A; }
    .credit-number-input:focus { border-color: var(--primary); }
    .quick-select { display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: 0.75rem; }
    .quick-btn {
        padding: 0.3rem 0.65rem;
        background: rgba(129,140,248,0.08);
        border: 1px solid rgba(129,140,248,0.2);
        border-radius: 99px; color: var(--primary);
        font-size: 0.75rem; font-weight: 600; cursor: pointer;
        transition: var(--transition); font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .quick-btn:hover { background: rgba(129,140,248,0.2); }
    .custom-price-row { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .custom-price-label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem; }
    .custom-price-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.75rem; font-weight: 800; color: var(--primary); }
    .btn-buy {
        flex: 1; min-width: 140px; justify-content: center; padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff; border-radius: 12px; border: none; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
        transition: var(--transition); box-shadow: 0 4px 15px var(--primary-glow);
    }
    .btn-buy:hover { transform: translateY(-2px); box-shadow: 0 8px 25px var(--primary-glow); }

    @media (max-width: 992px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) {
        .panel-wrap { padding: 1.5rem 4% 3rem; }
        .app-header { flex-direction: column; align-items: flex-start; }
        .stats-row { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
        .stat-value { font-size: 1.5rem; }
        .promo-bar { flex-direction: column; }
        .promo-link { max-width: 100%; width: 100%; text-align: center; }
        .tab-content { padding: 1rem; }
        .generations-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 0.75rem; }
        .credits-grid { grid-template-columns: 1fr 1fr; }
        .custom-credit-form { flex-direction: column; }
        .pagination-wrap { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 400px) {
        .credits-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="panel-wrap">

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

    <div class="tab-section">
        <div class="tab-nav-wrapper">
            <nav class="tab-nav">
                <button class="tab-btn active" onclick="switchTab(event, 'tab-history')">{{ __('tab_studio') }}</button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-transactions')">{{ __('tab_transactions') }}</button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-domains')">{{ __('tab_domains') }}</button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-credits')">{{ __('tab_credits') }}</button>
            </nav>
        </div>

        {{-- Studio History --}}
        <div id="tab-history" class="tab-content active">
            @if($recentGenerations->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">✨</div>
                    <p>{{ __('no_generations') }}</p>
                </div>
            @else
                <div class="generations-grid">
                    @foreach($recentGenerations as $gen)
                    <div class="gen-card">
                        @if($gen->result_image_path)
                            <img src="{{ Storage::url($gen->result_image_path) }}" alt="AI Gen">
                        @else
                            <div style="z-index:2;color:var(--text-muted);font-size:0.75rem;text-align:center;padding:0.5rem">{{ __('processing') }}</div>
                        @endif
                        <div class="gen-status">
                            <span class="badge badge-{{ $gen->status === 'completed' ? 'success' : ($gen->status === 'failed' ? 'danger' : 'warning') }}">
                                {{ $gen->status_label }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

               {{-- Generations pagination --}}
@if($recentGenerations->hasPages())
<div class="pagination-wrap">
    <span class="pagination-info">{{ $recentGenerations->firstItem() }}–{{ $recentGenerations->lastItem() }} / {{ $recentGenerations->total() }}</span>
    <div class="pagination-btns">
        @if(!$recentGenerations->onFirstPage())
            <a href="javascript:void(0)"
               onclick="loadPage('{{ $recentGenerations->appends(request()->except('gen_page'))->previousPageUrl() }}', 'tab-history', 'tab-history')"
               class="page-btn">{{ __('show_prev') }}</a>
        @endif
        @if($recentGenerations->hasMorePages())
            <a href="javascript:void(0)"
               onclick="loadPage('{{ $recentGenerations->appends(request()->except('gen_page'))->nextPageUrl() }}', 'tab-history', 'tab-history')"
               class="page-btn">{{ __('show_more') }}</a>
        @endif
    </div>
</div>
@endif
            @endif
        </div>

        {{-- Transactions --}}
        <div id="tab-transactions" class="tab-content">
            @if($recentTransactions->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">💳</div>
                    <p>{{ __('no_transactions') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('tx_package') }}</th>
                                <th>{{ __('tx_credit') }}</th>
                                <th>{{ __('tx_date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $tx)
                            <tr>
                                <td style="font-weight:500">{{ $tx->package?->translated_name ?? $tx->type }}</td>
                                <td style="color:var(--primary);font-weight:600">+{{ $tx->credit_amount }} {{ __('credits') }}</td>
                                <td>{{ $tx->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

     @if($recentTransactions->hasPages())
<div class="pagination-wrap">
    <span class="pagination-info">{{ $recentTransactions->firstItem() }}–{{ $recentTransactions->lastItem() }} / {{ $recentTransactions->total() }}</span>
    <div class="pagination-btns">
        @if(!$recentTransactions->onFirstPage())
            <a href="javascript:void(0)"
               onclick="loadPage('{{ $recentTransactions->appends(request()->except('tx_page'))->previousPageUrl() }}', 'tab-transactions', 'tab-transactions')"
               class="page-btn">{{ __('show_prev') }}</a>
        @endif
        @if($recentTransactions->hasMorePages())
            <a href="javascript:void(0)"
               onclick="loadPage('{{ $recentTransactions->appends(request()->except('tx_page'))->nextPageUrl() }}', 'tab-transactions', 'tab-transactions')"
               class="page-btn">{{ __('show_more') }}</a>
        @endif
    </div>
</div>
@endif
            @endif
        </div>

        {{-- Domains --}}
        <div id="tab-domains" class="tab-content">
            <div style="margin-bottom:1rem;display:flex;justify-content:flex-end">
                <button class="btn btn-outline" style="padding:0.4rem 1rem;font-size:0.8rem">{{ __('add_new') }}</button>
            </div>
            @if($domains->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">🌐</div>
                    <p>{{ __('no_domains') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('domain_name') }}</th>
                                <th>{{ __('domain_status') }}</th>
                                <th>{{ __('domain_expires') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($domains as $domain)
                            <tr>
                                <td style="font-family:monospace;color:var(--text-bright)">{{ $domain->full_domain }}</td>
                                <td>
                                    <span class="badge badge-{{ $domain->status === 'active' ? 'success' : ($domain->status === 'pending' ? 'warning' : ($domain->status === 'rejected' ? 'danger' : 'gray')) }}">
                                        {{ $domain->status_label }}
                                    </span>
                                </td>
                                <td>{{ $domain->expires_at?->format('d.m.Y') ?? __('domain_unlimited') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

             @if($domains->hasPages())
<div class="pagination-wrap">
    <span class="pagination-info">{{ $domains->firstItem() }}–{{ $domains->lastItem() }} / {{ $domains->total() }}</span>
    <div class="pagination-btns">
        @if(!$domains->onFirstPage())
            <a href="javascript:void(0)"
               onclick="loadPage('{{ $domains->appends(request()->except('domain_page'))->previousPageUrl() }}', 'tab-domains', 'tab-domains')"
               class="page-btn">{{ __('show_prev') }}</a>
        @endif
        @if($domains->hasMorePages())
            <a href="javascript:void(0)"
               onclick="loadPage('{{ $domains->appends(request()->except('domain_page'))->nextPageUrl() }}', 'tab-domains', 'tab-domains')"
               class="page-btn">{{ __('show_more') }}</a>
        @endif
    </div>
</div>
@endif
            @endif
        </div>

        {{-- Credits --}}
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
                    <a href="{{ route('payment.show', $package->slug) }}"
                       class="btn {{ $package->is_featured ? 'btn-primary' : 'btn-outline' }}"
                       style="width:100%;justify-content:center;margin-top:auto">
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
                            <input type="number" class="credit-number-input" id="custom-credit-amount"
                                value="10" min="1" max="1000" oninput="updateCustomPrice()">
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

    </div>
</div>
@endsection


{{-- Yeni Deneme Modal --}}
<div id="tryon-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div style="background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:24px;padding:2rem;width:min(560px,95vw);max-height:90vh;overflow-y:auto;position:relative;backdrop-filter:blur(20px);">

        {{-- Kapatma --}}
        <button onclick="closeTryOn()" style="position:absolute;top:1rem;right:1rem;background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:50%;width:32px;height:32px;cursor:pointer;color:var(--text-muted);font-size:1.2rem;display:flex;align-items:center;justify-content:center;">×</button>

        {{-- Başlık --}}
        <h2 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.3rem;font-weight:800;background:linear-gradient(135deg,#fff 0%,#A5B4FC 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:0.25rem">{{ __('new_try') }}</h2>
        <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1.5rem">{{ __('balance') }}: <strong style="color:var(--primary)">{{ $user->credit_balance }} {{ __('credits') }}</strong> — 1 {{ __('credits') }} / deneme</p>

        {{-- Adım 1: Fotoğraf Yükleme --}}
        <div id="step-upload">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">

                {{-- Kişi Fotoğrafı --}}
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">👤 Kişi Fotoğrafı</label>
                    <div id="person-drop" onclick="document.getElementById('person-input').click()"
                        style="border:2px dashed var(--glass-border);border-radius:16px;aspect-ratio:3/4;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s;position:relative;overflow:hidden;background:rgba(0,0,0,0.2)">
                        <img id="person-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
                        <div id="person-placeholder" style="text-align:center;padding:1rem;z-index:1">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5" style="margin:0 auto 0.5rem;display:block"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span style="font-size:0.75rem;color:var(--text-muted)">Tıkla veya sürükle</span>
                        </div>
                    </div>
                    <input type="file" id="person-input" accept="image/*" style="display:none" onchange="previewImage(this,'person')">
                </div>

                {{-- Kıyafet Fotoğrafı --}}
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">👗 Kıyafet Fotoğrafı</label>
                    <div id="garment-drop" onclick="document.getElementById('garment-input').click()"
                        style="border:2px dashed var(--glass-border);border-radius:16px;aspect-ratio:3/4;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:all 0.3s;position:relative;overflow:hidden;background:rgba(0,0,0,0.2)">
                        <img id="garment-preview" src="" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
                        <div id="garment-placeholder" style="text-align:center;padding:1rem;z-index:1">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5" style="margin:0 auto 0.5rem;display:block"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/></svg>
                            <span style="font-size:0.75rem;color:var(--text-muted)">Tıkla veya sürükle</span>
                        </div>
                    </div>
                    <input type="file" id="garment-input" accept="image/*" style="display:none" onchange="previewImage(this,'garment')">
                </div>
            </div>

            {{-- Prompt --}}
            <div style="margin-bottom:1.25rem">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.4rem">Açıklama (İsteğe Bağlı)</label>
                <input type="text" id="tryon-prompt" placeholder="Örn: Gömleği kişinin üzerine giydir..."
                    style="width:100%;background:rgba(0,0,0,0.3);border:1px solid var(--glass-border);border-radius:10px;padding:0.75rem 1rem;color:var(--text-bright);font-family:'Inter',sans-serif;font-size:0.9rem;outline:none;transition:border-color 0.2s">
            </div>

            {{-- Hata mesajı --}}
            <div id="tryon-error" style="display:none;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#FCA5A5;padding:0.75rem 1rem;border-radius:10px;font-size:0.85rem;margin-bottom:1rem"></div>

            {{-- Başlat Butonu --}}
            <button id="tryon-start-btn" onclick="startTryOn()"
                style="width:100%;padding:0.875rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:none;border-radius:12px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:1rem;cursor:pointer;transition:all 0.3s;box-shadow:0 4px 15px var(--primary-glow)">
                🚀 Denemeyi Başlat — 1 Kredi
            </button>
        </div>

        {{-- Adım 2: İşleme --}}
        <div id="step-processing" style="display:none;text-align:center;padding:1rem 0">
            <div style="margin-bottom:1.5rem">
                <div style="font-size:2.5rem;margin-bottom:0.75rem">✨</div>
                <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;color:var(--text-bright);margin-bottom:0.25rem">Yapay Zeka Çalışıyor...</h3>
                <p style="color:var(--text-muted);font-size:0.85rem">Lütfen bekleyin, görsel oluşturuluyor.</p>
            </div>

            {{-- Progress Bar --}}
            <div style="background:rgba(0,0,0,0.3);border-radius:99px;height:8px;margin-bottom:0.75rem;overflow:hidden">
                <div id="progress-bar" style="height:100%;width:0%;background:linear-gradient(90deg,var(--primary),var(--primary-dark));border-radius:99px;transition:width 0.5s ease"></div>
            </div>
            <div id="progress-text" style="color:var(--primary);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:1.1rem;margin-bottom:0.5rem">%0</div>
            <div id="progress-status" style="color:var(--text-muted);font-size:0.8rem">Bağlanıyor...</div>

            {{-- İptal --}}
            <button onclick="cancelTryOn()" style="margin-top:1.5rem;background:transparent;border:1px solid var(--glass-border);color:var(--text-muted);padding:0.5rem 1.25rem;border-radius:99px;cursor:pointer;font-size:0.85rem;transition:all 0.3s">
                İptal Et
            </button>
        </div>

        {{-- Adım 3: Sonuç --}}
        <div id="step-result" style="display:none;text-align:center">
            <div style="margin-bottom:1rem">
                <span style="font-size:1.5rem">🎉</span>
                <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;color:var(--text-bright);margin:0.5rem 0 0.25rem">Hazır!</h3>
                <p style="color:var(--text-muted);font-size:0.85rem">Sanal deneme tamamlandı.</p>
            </div>
            <div style="border-radius:16px;overflow:hidden;margin-bottom:1.25rem;border:1px solid var(--glass-border)">
                <img id="result-image" src="" alt="Sonuç" style="width:100%;max-height:500px;object-fit:contain;display:block">
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap">
                <a id="result-download" href="#" download="tryon-result.png"
                    style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.75rem 1.5rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border-radius:12px;text-decoration:none;font-weight:700;font-size:0.9rem">
                    ⬇️ İndir
                </a>
                <button onclick="resetTryOn()"
                    style="padding:0.75rem 1.5rem;background:var(--glass-bg);border:1px solid var(--glass-border);color:var(--text-bright);border-radius:12px;cursor:pointer;font-weight:600;font-size:0.9rem">
                    🔄 Yeni Deneme
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
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
                // Kredi sekmesine geç
                switchTabById('tab-credits');
                closeTryOn();
            } else {
                showError(data.message || 'Bir hata oluştu.');
            }
            return;
        }

        currentGenId = data.generation_id;
        startPolling(currentGenId);

        // 60 saniye timeout
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
                    // Stüdyo geçmişini yenile
                    refreshStudio();
                }, 500);

            } else if (data.status === 'failed') {
                clearInterval(pollingTimer);
                clearTimeout(timeoutTimer);
                showStep('upload');
                document.getElementById('tryon-start-btn').disabled = false;
                showError(data.message || 'İşlem başarısız. Krediniz iade edildi.');

            } else {
                // İşleniyor
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
    // AJAX ile stüdyo geçmişini güncelle
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

// ESC ile modal kapat
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

// Sayfa yüklenince aktif tab'ı geri yükle
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

// AJAX Pagination
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
</script>
@endpush