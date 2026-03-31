@extends('layouts.app')

@section('title', __('site_name') . ' - ' . __('tagline'))

@push('styles')
<style>
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-15px)} }
    @keyframes pulse-glow { 0%{box-shadow:0 0 0 0 var(--primary-glow)} 70%{box-shadow:0 0 0 15px rgba(129,140,248,0)} 100%{box-shadow:0 0 0 0 rgba(129,140,248,0)} }
    @keyframes scan { 0%{transform:translateY(-100%)} 100%{transform:translateY(100%)} }

    .text-gradient {
        background: linear-gradient(135deg, var(--text-bright) 0%, #A5B4FC 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }

    /* HERO */
    .hero {
        padding: 6rem 5% 5rem;
        min-height: 90vh; display: flex; align-items: center;
        position: relative; overflow: hidden;
    }
    .hero::before {
        content:''; position:absolute; top:-20%; left:50%; transform:translateX(-50%);
        width:800px; height:800px;
        background:radial-gradient(circle,rgba(99,102,241,0.15) 0%,rgba(2,6,23,0) 70%);
        border-radius:50%; z-index:0; pointer-events:none;
    }
    .hero-container {
        max-width:1200px; margin:0 auto; position:relative; z-index:10; width:100%;
        display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:center;
    }
    .hero-badge {
        display:inline-flex; align-items:center; gap:0.5rem;
        font-size:0.875rem; font-weight:600; color:#E0E7FF;
        background:var(--glass-bg); padding:0.5rem 1.25rem;
        border-radius:999px; margin-bottom:2rem; border:1px solid var(--glass-border);
        backdrop-filter:blur(10px);
    }
    .hero-badge-dot {
        width:8px; height:8px; border-radius:50%;
        background:var(--primary); box-shadow:0 0 10px var(--primary);
        animation: pulse-glow 2s infinite;
    }
    .hero h1 { font-size:clamp(2.2rem,5vw,4.5rem); font-weight:800; line-height:1.1; letter-spacing:-0.02em; margin-bottom:1.5rem; }
    .hero p { font-size:1.125rem; color:var(--text-muted); margin-bottom:2.5rem; max-width:520px; font-weight:300; }
    .hero-cta { display:flex; gap:1rem; flex-wrap:wrap; }

    /* Demo Visual */
    .demo-visual-wrap { animation:float 6s ease-in-out infinite; max-width:100%; }
    .demo-visual {
        background:rgba(15,23,42,0.6); backdrop-filter:blur(20px);
        padding:1.5rem; border-radius:32px;
        border:1px solid rgba(255,255,255,0.1);
        display:flex; align-items:center; justify-content:space-between; gap:1rem;
        box-shadow:0 25px 50px -12px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.1);
        transform:rotateY(-5deg) rotateX(5deg);
    }
    .demo-img-box {
        flex:1; aspect-ratio:3/4; background:rgba(0,0,0,0.3); border-radius:16px;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        color:var(--text-muted); font-size:0.875rem; font-weight:500;
        border:1px dashed var(--glass-border); position:relative; overflow:hidden; text-align:center; padding:0.5rem;
    }
    .demo-img-box.result {
        background:rgba(129,140,248,0.1); border:1px solid var(--primary); color:var(--primary);
        box-shadow:0 0 20px var(--primary-glow);
    }
    .demo-img-box.result::after {
        content:''; position:absolute; top:-50%; left:-50%; width:200%; height:200%;
        background:linear-gradient(to bottom,transparent,rgba(129,140,248,0.2),transparent);
        transform:rotate(30deg); animation:scan 3s linear infinite;
    }
    .demo-icon {
        color:var(--primary); background:rgba(129,140,248,0.1);
        width:40px; height:40px; border-radius:50%;
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }

    /* Sections */
    .section { max-width:1200px; margin:0 auto; padding:7rem 5%; position:relative; z-index:10; overflow-x:clip; }
    .section-header { text-align:center; margin-bottom:4rem; max-width:700px; margin-inline:auto; }
    .section-label { font-family:'Plus Jakarta Sans',sans-serif; font-size:0.875rem; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:2px; margin-bottom:1rem; display:block; }
    .section-title { font-size:clamp(2rem,4vw,3rem); font-weight:800; color:var(--text-bright); margin-bottom:1rem; line-height:1.2; }
    .section-desc { color:var(--text-muted); font-size:1.125rem; font-weight:400; }

    /* Glass Grid */
    .glass-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,250px),1fr)); gap:2rem; }
    .glass-card {
        padding:2.5rem; background:var(--glass-bg); border:1px solid var(--glass-border);
        border-radius:24px; backdrop-filter:blur(12px); transition:var(--transition);
        position:relative; overflow:hidden; width:100%;
    }
    .glass-card::before {
        content:''; position:absolute; top:0; left:0; width:100%; height:1px;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,0.3),transparent);
        opacity:0; transition:var(--transition);
    }
    .glass-card:hover { transform:translateY(-5px); border-color:var(--glass-border-hover); background:rgba(30,41,59,0.6); }
    .glass-card:hover::before { opacity:1; }
    .card-icon {
        width:56px; height:56px;
        background:linear-gradient(135deg,rgba(129,140,248,0.2) 0%,rgba(99,102,241,0.05) 100%);
        color:var(--primary); border-radius:16px;
        display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem;
        border:1px solid rgba(129,140,248,0.2);
    }
    .glass-card h3 { font-size:1.25rem; font-weight:700; margin-bottom:0.75rem; color:var(--text-bright); }
    .glass-card p { color:var(--text-muted); font-size:1rem; font-weight:300; }

    /* Packages */
    .packages-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(min(100%,280px),1fr)); gap:2rem; align-items:center; }
    .pkg-card {
        background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:32px;
        padding:3.5rem 2.5rem; position:relative; transition:var(--transition);
        display:flex; flex-direction:column; height:100%; backdrop-filter:blur(12px); width:100%;
    }
    .pkg-card:hover { border-color:rgba(255,255,255,0.2); transform:translateY(-5px); }
    .pkg-card.featured {
        border:1px solid var(--primary);
        background:linear-gradient(180deg,rgba(30,41,59,0.8) 0%,rgba(2,6,23,0.9) 100%);
        box-shadow:0 0 30px rgba(99,102,241,0.15); transform:scale(1.05); z-index:1;
    }
    .pkg-badge {
        position:absolute; top:-14px; left:50%; transform:translateX(-50%); width:max-content;
        background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%); color:#fff;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:0.75rem; font-weight:800;
        text-transform:uppercase; letter-spacing:1px; padding:0.5rem 1.25rem;
        border-radius:999px; box-shadow:0 4px 15px var(--primary-glow);
    }
    .pkg-name { font-size:1.5rem; font-weight:700; margin-bottom:0.5rem; color:var(--text-bright); }
    .pkg-desc { font-size:0.95rem; color:var(--text-muted); margin-bottom:2.5rem; min-height:2.5rem; font-weight:300; }
    .pkg-price { font-family:'Plus Jakarta Sans',sans-serif; font-size:3.5rem; font-weight:800; color:var(--text-bright); margin-bottom:0.5rem; display:flex; align-items:flex-start; }
    .pkg-price span { font-size:1.25rem; font-weight:600; margin-top:0.75rem; color:var(--text-muted); }
    .pkg-credits { font-size:0.875rem; font-weight:600; color:var(--primary); margin-bottom:2rem; padding-bottom:2rem; border-bottom:1px solid var(--glass-border); }
    .pkg-features { list-style:none; flex:1; margin-bottom:2.5rem; }
    .pkg-features li { display:flex; align-items:center; gap:1rem; font-size:1rem; color:var(--text-main); padding:0.6rem 0; font-weight:300; }
    .check-icon { color:var(--primary); flex-shrink:0; background:rgba(129,140,248,0.1); border-radius:50%; padding:2px; }
    .btn-block { width:100%; text-align:center; }

    /* FAQ */
    .faq-container { max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:1rem; width:100%; }
    .faq-item { background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:16px; backdrop-filter:blur(10px); transition:var(--transition); }
    .faq-item:hover { border-color:var(--glass-border-hover); }
    .faq-item summary { padding:1.5rem; font-weight:600; font-size:1.05rem; cursor:pointer; list-style:none; color:var(--text-bright); display:flex; justify-content:space-between; align-items:center; user-select:none; }
    .faq-item summary::-webkit-details-marker { display:none; }
    .faq-item summary::after { content:'+'; color:var(--primary); font-size:1.5rem; font-weight:300; transition:transform 0.4s ease; flex-shrink:0; margin-left:1rem; }
    .faq-item[open] summary::after { transform:rotate(45deg); }
    .faq-item p { padding:0 1.5rem 1.5rem; color:var(--text-muted); font-size:0.95rem; font-weight:300; margin-top:0.5rem; }

    /* CTA */
    .cta-section {
        background:radial-gradient(circle at center,rgba(99,102,241,0.2) 0%,rgba(2,6,23,0) 100%),var(--glass-bg);
        border:1px solid var(--glass-border); backdrop-filter:blur(20px);
        text-align:center; padding:5rem 5%; margin:4rem 5%; border-radius:32px; position:relative; overflow:hidden;
    }
    .cta-section::before { content:''; position:absolute; top:0; left:0; width:100%; height:2px; background:linear-gradient(90deg,transparent,var(--primary),transparent); }
    .cta-section h2 { font-size:clamp(1.8rem,4vw,3rem); font-weight:800; margin-bottom:1.5rem; color:var(--text-bright); }
    .cta-section p { color:var(--text-muted); margin-bottom:3rem; font-size:1.125rem; max-width:600px; margin-inline:auto; font-weight:300; }

    /* Footer */
    .footer { background:rgba(2,6,23,0.8); backdrop-filter:blur(10px); padding:4rem 5% 2rem; border-top:1px solid var(--glass-border); position:relative; z-index:10; }
    .footer-grid { max-width:1200px; margin:0 auto; display:grid; grid-template-columns:2fr 1fr 1fr; gap:3rem; margin-bottom:4rem; }
    .footer-brand h3 { font-family:'Plus Jakarta Sans',sans-serif; font-size:1.75rem; font-weight:800; color:#fff; margin-bottom:1rem; letter-spacing:-1px; }
    .footer-brand h3 span { color:var(--primary); }
    .footer-brand p { color:var(--text-muted); font-size:0.95rem; max-width:320px; font-weight:300; }
    .footer-links h4 { color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:1.5rem; font-size:1.1rem; }
    .footer-links ul { list-style:none; }
    .footer-links ul li { margin-bottom:1rem; }
    .footer-links ul li a { color:var(--text-muted); text-decoration:none; transition:var(--transition); font-size:0.95rem; }
    .footer-links ul li a:hover { color:var(--primary); padding-left:5px; }
    .footer-bottom { max-width:1200px; margin:0 auto; padding-top:2rem; border-top:1px solid var(--glass-border); text-align:center; color:var(--text-muted); font-size:0.875rem; }

   html.light .hero::before { background:radial-gradient(circle,rgba(99,102,241,0.12) 0%,rgba(240,244,255,0) 70%); }
html.light .demo-visual { background:rgba(255,255,255,0.95); border-color:rgba(99,102,241,0.15); box-shadow:0 25px 50px -12px rgba(99,102,241,0.15); }
html.light .demo-img-box { background:rgba(240,244,255,0.8); border-color:rgba(99,102,241,0.2); color:#475569; }
html.light .demo-img-box.result { background:rgba(99,102,241,0.08); border-color:var(--primary); }
html.light .demo-icon { background:rgba(99,102,241,0.1); }
html.light .glass-card { background:rgba(255,255,255,0.9); border-color:rgba(99,102,241,0.12); box-shadow:0 4px 20px rgba(99,102,241,0.08); }
html.light .glass-card:hover { background:#fff; border-color:rgba(99,102,241,0.3); box-shadow:0 8px 30px rgba(99,102,241,0.15); }
html.light .card-icon { background:linear-gradient(135deg,rgba(99,102,241,0.15) 0%,rgba(99,102,241,0.05) 100%); border-color:rgba(99,102,241,0.2); }
html.light .pkg-card { background:rgba(255,255,255,0.9); border-color:rgba(99,102,241,0.12); box-shadow:0 4px 20px rgba(99,102,241,0.06); }
html.light .pkg-card.featured { background:linear-gradient(180deg,rgba(240,244,255,1) 0%,rgba(255,255,255,1) 100%); border-color:var(--primary); box-shadow:0 0 40px rgba(99,102,241,0.2); }
html.light .pkg-card:hover { box-shadow:0 8px 30px rgba(99,102,241,0.15); }
html.light .faq-item { background:rgba(255,255,255,0.9); border-color:rgba(99,102,241,0.12); }
html.light .faq-item:hover { border-color:rgba(99,102,241,0.3); box-shadow:0 4px 15px rgba(99,102,241,0.08); }
html.light .cta-section { background:linear-gradient(135deg,rgba(99,102,241,0.08) 0%,rgba(240,244,255,0.9) 100%); border-color:rgba(99,102,241,0.2); }
html.light .footer { background:rgba(240,244,255,0.98); border-color:rgba(99,102,241,0.15); }
html.light .btn-outline { background:rgba(255,255,255,0.9); border-color:rgba(99,102,241,0.2); color:#0F172A; }
html.light .btn-outline:hover { border-color:var(--primary); background:rgba(99,102,241,0.05); }
html.light .text-gradient { background:linear-gradient(135deg,#1E293B 0%,#6366F1 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
html.light body { background-image:linear-gradient(rgba(99,102,241,0.06) 1px,transparent 1px), linear-gradient(90deg,rgba(99,102,241,0.06) 1px,transparent 1px); }
    
html.light .hero-badge { background:rgba(99,102,241,0.1); border-color:rgba(99,102,241,0.25); color:#4338CA; }
html.light .hero-badge-dot { background:#6366F1; box-shadow:0 0 10px rgba(99,102,241,0.5); }
html.light .nav-credit { background:rgba(99,102,241,0.1); border-color:rgba(99,102,241,0.25); color:#4338CA; }
html.light .lang-switcher { background:rgba(255,255,255,0.9); border-color:rgba(99,102,241,0.2); }
html.light .lang-btn { color:#475569; }
html.light .theme-toggle { background:rgba(255,255,255,0.9); border-color:rgba(99,102,241,0.2); color:#475569; }
html.light .theme-toggle:hover { border-color:var(--primary); color:var(--primary); }
html.light .section-label { color:#4338CA; }
html.light .pkg-credits { color:#4338CA; }
html.light .check-icon { color:#4338CA; background:rgba(99,102,241,0.1); }
html.light .pkg-badge { box-shadow:0 4px 15px rgba(99,102,241,0.3); }
html.light .faq-item summary { color:#0F172A; }
html.light .faq-item summary::after { color:#6366F1; }





/* Responsive */
    @media (max-width:992px) {
        .hero-container { grid-template-columns:1fr; text-align:center; }
        .hero p { margin:0 auto 2.5rem; }
        .hero-cta { justify-content:center; }
        .demo-visual-wrap { margin:0 auto; max-width:480px; animation:none; }
        .demo-visual { transform:none; }
        .pkg-card.featured { transform:scale(1); }
        .footer-grid { grid-template-columns:1fr 1fr; }
        .footer-brand { grid-column:1/-1; }
    }
    @media (max-width:768px) {
        .hero { padding:5rem 5% 3rem; min-height:auto; }
        .section { padding:4rem 5%; }
        .demo-visual { flex-direction:column; }
        .demo-icon { transform:rotate(90deg); }
        .demo-img-box { width:100%; min-height:180px; }
        .cta-section { margin:2rem 5%; padding:3rem 1.5rem; }
        .footer-grid { grid-template-columns:1fr; gap:2rem; }
    }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="hero">
    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                {{ __('ai_active') }}
            </div>
            <h1 class="text-gradient">{{ __('tagline') }}</h1>
            <p>{{ __('hero_desc') }}</p>
            <div class="hero-cta">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">{{ __('go_to_studio') }}</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">{{ __('get_started') }}</a>
                    <a href="#nasil-calisir" class="btn btn-outline btn-lg">{{ __('explore') }}</a>
                @endauth
            </div>
        </div>

        <div class="demo-visual-wrap">
            <div class="demo-visual">
                <div class="demo-img-box">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span style="margin-top:10px">{{ __('ref_image') }}</span>
                </div>
                <div class="demo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
                <div class="demo-img-box result">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    <span style="margin-top:10px;z-index:2">{{ __('generating') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TEKNOLOJİ --}}
<section class="section">
    <div style="position:absolute;top:50%;left:0;width:300px;height:300px;background:var(--primary);filter:blur(150px);opacity:0.08;z-index:-1;pointer-events:none;"></div>
    <div class="section-header">
        <span class="section-label">{{ __('tech_label') }}</span>
        <h2 class="section-title text-gradient">{{ __('tech_title') }}</h2>
        <p class="section-desc">{{ __('tech_desc') }}</p>
    </div>
    <div class="glass-grid">
        <div class="glass-card">
            <div class="card-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
            <h3>{{ __('feat_1_title') }}</h3>
            <p>{{ __('feat_1_desc') }}</p>
        </div>
        <div class="glass-card">
            <div class="card-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <h3>{{ __('feat_2_title') }}</h3>
            <p>{{ __('feat_2_desc') }}</p>
        </div>
        <div class="glass-card">
            <div class="card-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg></div>
            <h3>{{ __('feat_3_title') }}</h3>
            <p>{{ __('feat_3_desc') }}</p>
        </div>
    </div>
</section>

{{-- NASIL ÇALIŞIR --}}
<section id="nasil-calisir" class="section">
    <div class="section-header">
        <span class="section-label">{{ __('steps_label') }}</span>
        <h2 class="section-title text-gradient">{{ __('steps_title') }}</h2>
        <p class="section-desc">{{ __('steps_desc') }}</p>
    </div>
    <div class="glass-grid">
        <div class="glass-card" style="text-align:center">
            <div class="card-icon" style="margin:0 auto 1.5rem;border-radius:50%;width:64px;height:64px;font-size:1.5rem;font-weight:700">1</div>
            <h3>{{ __('step_1_title') }}</h3>
            <p>{{ __('step_1_desc') }}</p>
        </div>
        <div class="glass-card" style="text-align:center">
            <div class="card-icon" style="margin:0 auto 1.5rem;border-radius:50%;width:64px;height:64px;font-size:1.5rem;font-weight:700">2</div>
            <h3>{{ __('step_2_title') }}</h3>
            <p>{{ __('step_2_desc') }}</p>
        </div>
        <div class="glass-card" style="text-align:center">
            <div class="card-icon" style="margin:0 auto 1.5rem;border-radius:50%;width:64px;height:64px;font-size:1.5rem;font-weight:700">3</div>
            <h3>{{ __('step_3_title') }}</h3>
            <p>{{ __('step_3_desc') }}</p>
        </div>
    </div>
</section>

{{-- PAKETLER --}}
<section id="paketler" class="section">
    <div style="position:absolute;top:20%;right:0;width:400px;height:400px;background:var(--primary);filter:blur(200px);opacity:0.08;z-index:-1;pointer-events:none;"></div>
    <div class="section-header">
        <span class="section-label">{{ __('pricing_label') }}</span>
        <h2 class="section-title text-gradient">{{ __('pricing_title') }}</h2>
        <p class="section-desc">{{ __('pricing_desc') }}</p>
    </div>
    <div class="packages-grid">
        @php $packages = \App\Models\Package::active()->get() ?? collect(); @endphp
        @forelse($packages as $package)
        <div class="pkg-card {{ $package->is_featured ? 'featured' : '' }}">
       @if($package->badge_label)
    <div class="pkg-badge">{{ $package->translated_badge }}</div>
@endif
           <div class="pkg-name">{{ $package->translated_name }}</div>
<div class="pkg-desc">{{ $package->translated_desc }}</div>
            <div class="pkg-price">{{ number_format($package->price / 100, 0, ',', '.') }}<span>₺</span></div>
            <div class="pkg-credits">{{ $package->credit_amount }} {{ __('ai_credits') }}</div>
            @if($package->features)
            <ul class="pkg-features">
              @foreach($package->translated_features as $feature)
<li>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
    {{ $feature }}
</li>
@endforeach
            </ul>
            @endif
            <a href="{{ route('package.select', $package->slug) }}" class="btn btn-block {{ $package->is_featured ? 'btn-primary' : 'btn-outline' }}">
                {{ __('select_package') }}
            </a>
        </div>
        @empty
        <div style="grid-column:1/-1;padding:5rem;text-align:center;background:var(--glass-bg);border-radius:32px;border:1px dashed rgba(255,255,255,0.2);">
            <p style="color:var(--text-muted);font-size:1.1rem">Paketler yakında eklenecek.</p>
        </div>
        @endforelse
    </div>
</section>

{{-- SSS --}}
<section class="section">
    <div class="section-header">
        <span class="section-label">{{ __('faq_label') }}</span>
        <h2 class="section-title text-gradient">{{ __('faq_title') }}</h2>
    </div>
    <div class="faq-container">
        <details class="faq-item">
            <summary>{{ __('faq_1_q') }}</summary>
            <p>{{ __('faq_1_a') }}</p>
        </details>
        <details class="faq-item">
            <summary>{{ __('faq_2_q') }}</summary>
            <p>{{ __('faq_2_a') }}</p>
        </details>
        <details class="faq-item">
            <summary>{{ __('faq_3_q') }}</summary>
            <p>{{ __('faq_3_a') }}</p>
        </details>
        <details class="faq-item">
            <summary>{{ __('faq_4_q') }}</summary>
            <p>{{ __('faq_4_a') }}</p>
        </details>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <h2 class="text-gradient">{{ __('cta_title') }}</h2>
    <p>{{ __('cta_desc') }}</p>
    <a href="{{ route('register') }}" class="btn btn-primary btn-lg" style="box-shadow:0 0 30px rgba(129,140,248,0.4)">{{ __('cta_btn') }}</a>
</section>

{{-- FOOTER --}}
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <h3>Try<span>On</span></h3>
            <p>{{ __('footer_desc') }}</p>
        </div>
        <div class="footer-links">
            <h4>{{ __('footer_platform') }}</h4>
            <ul>
                <li><a href="#nasil-calisir">{{ __('how_it_works') }}</a></li>
                <li><a href="#paketler">{{ __('pricing') }}</a></li>
                <li><a href="#">{{ __('api_docs') }}</a></li>
                <li><a href="{{ route('login') }}">{{ __('login') }}</a></li>
            </ul>
        </div>
        <div class="footer-links">
            <h4>{{ __('footer_corporate') }}</h4>
            <ul>
                <li><a href="#">{{ __('about') }}</a></li>
                <li><a href="#">{{ __('privacy') }}</a></li>
                <li><a href="#">{{ __('terms') }}</a></li>
                <li><a href="#">{{ __('contact') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} TryOn. {{ __('copyright') }}
    </div>
</footer>

@endsection