<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('site_name'))</title>
    <meta name="description" content="{{ \App\Models\Setting::get('meta_description') }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #020617;
            --primary: #818CF8;
            --primary-dark: #6366F1;
            --primary-glow: rgba(129, 140, 248, 0.35);
            --text-bright: #FFFFFF;
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --glass-bg: rgba(30, 41, 59, 0.4);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-border-hover: rgba(255, 255, 255, 0.15);
            --nav-bg: rgba(2, 6, 23, 0.8);
            --surface: rgba(15, 23, 42, 0.6);
            --radius-md: 16px;
            --radius-lg: 24px;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html.light {
            --bg-main: #E8EEFF;
            --primary: #6366F1;
            --primary-dark: #4F46E5;
            --primary-glow: rgba(99, 102, 241, 0.3);
            --text-bright: #0F172A;
            --text-main: #1E293B;
            --text-muted: #475569;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(99, 102, 241, 0.15);
            --glass-border-hover: rgba(99, 102, 241, 0.35);
            --nav-bg: rgba(240, 244, 255, 0.92);
            --surface: rgba(255, 255, 255, 0.9);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { max-width: 100vw; overflow-x: hidden; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
            transition: background-color 0.4s, color 0.4s;
            background-image: linear-gradient(var(--glass-border) 1px, transparent 1px),
                              linear-gradient(90deg, var(--glass-border) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        h1, h2, h3, h4 { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── NAV ── */
        .main-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            background: var(--nav-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 0 5%;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            transition: var(--transition);
        }

        .nav-brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.4rem; font-weight: 800;
            color: var(--text-bright);
            text-decoration: none;
            letter-spacing: -0.5px;
            flex-shrink: 0;
        }
        .nav-brand span { color: var(--primary); }

        /* Desktop nav links */
        .nav-center {
            display: flex; align-items: center; gap: 2rem;
        }
        .nav-center a {
            color: var(--text-muted); text-decoration: none;
            font-size: 0.9rem; font-weight: 500; transition: color 0.2s;
            white-space: nowrap;
        }
        .nav-center a:hover { color: var(--text-bright); }

        /* Desktop right */
        .nav-right {
            display: flex; align-items: center; gap: 0.6rem; flex-shrink: 0;
        }

        /* Butonlar */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            text-decoration: none; font-weight: 600; font-size: 0.875rem;
            padding: 0.55rem 1.2rem; border-radius: 99px;
            transition: var(--transition); cursor: pointer; border: none;
            font-family: 'Inter', sans-serif; white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff; box-shadow: 0 4px 15px -2px var(--primary-glow);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px -2px var(--primary-glow); }
        .btn-outline {
            background: var(--glass-bg); color: var(--text-bright);
            border: 1px solid var(--glass-border); backdrop-filter: blur(10px);
        }
        .btn-outline:hover { border-color: var(--primary); background: rgba(99,102,241,0.05); }
        .btn-sm { padding: 0.45rem 1rem; font-size: 0.8rem; }
        .btn-lg { padding: 1.125rem 2.5rem; font-size: 1.125rem; }

        /* Kredi badge */
        .nav-credit {
            display: flex; align-items: center; gap: 0.4rem;
            background: rgba(129,140,248,0.1);
            border: 1px solid rgba(129,140,248,0.2);
            color: var(--primary);
            padding: 0.4rem 0.9rem;
            border-radius: 99px;
            font-size: 0.8rem; font-weight: 600;
            white-space: nowrap;
        }

        /* Dark/Light Toggle */
        .theme-toggle {
            width: 36px; height: 36px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-muted);
            transition: var(--transition); flex-shrink: 0;
        }
        .theme-toggle:hover { border-color: var(--primary); color: var(--primary); }

        /* Dil seçici */
        .lang-switcher {
            display: flex; align-items: center; gap: 0.2rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 99px; padding: 0.2rem;
        }
        .lang-btn {
            font-size: 0.72rem; font-weight: 700;
            padding: 0.22rem 0.55rem; border-radius: 99px;
            text-decoration: none; color: var(--text-muted);
            transition: var(--transition);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .lang-btn.active { background: var(--primary); color: #fff; }
        .lang-btn:hover:not(.active) { color: var(--text-bright); }

        /* ── MOBİL HAMBURGER ── */
        .nav-hamburger {
            display: none;
            flex-direction: column; justify-content: center; align-items: center;
            gap: 5px; cursor: pointer;
            width: 36px; height: 36px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            transition: var(--transition);
        }
        .nav-hamburger span {
            display: block; width: 18px; height: 2px;
            background: var(--text-muted); border-radius: 2px;
            transition: var(--transition);
        }
        .nav-hamburger:hover { border-color: var(--primary); }
        .nav-hamburger:hover span { background: var(--primary); }

        /* ── MOBİL DRAWER ── */
        .mobile-drawer {
            position: fixed; top: 0; right: -100%; bottom: 0;
            width: min(280px, 85vw);
            background: rgba(10, 15, 30, 0.98);
            backdrop-filter: blur(20px);
            border-left: 1px solid var(--glass-border);
            z-index: 2000;
            transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; flex-direction: column;
            padding: 1.5rem;
            gap: 1rem;
        }
        .mobile-drawer.open { right: 0; }

        .drawer-overlay {
            position: fixed; inset: 0; z-index: 1999;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(2px);
            opacity: 0; pointer-events: none;
            transition: opacity 0.35s ease;
        }
        .drawer-overlay.open { opacity: 1; pointer-events: all; }

        .drawer-header {
            display: flex; align-items: center; justify-content: space-between;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--glass-border);
        }
        .drawer-close {
            width: 32px; height: 32px;
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            border-radius: 8px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); transition: var(--transition);
        }
        .drawer-close:hover { border-color: var(--primary); color: var(--primary); }

        .drawer-nav { display: flex; flex-direction: column; gap: 0.25rem; flex: 1; }
        .drawer-nav a {
            color: var(--text-muted); text-decoration: none;
            font-size: 0.95rem; font-weight: 500;
            padding: 0.75rem 1rem; border-radius: 10px;
            transition: var(--transition);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .drawer-nav a:hover { color: var(--text-bright); background: var(--glass-bg); }

        .drawer-divider { height: 1px; background: var(--glass-border); margin: 0.5rem 0; }

        .drawer-bottom { display: flex; flex-direction: column; gap: 0.75rem; }
        .drawer-controls { display: flex; align-items: center; justify-content: space-between; }
        .drawer-actions { display: flex; flex-direction: column; gap: 0.5rem; }
        .drawer-actions .btn { width: 100%; justify-content: center; }

        @auth
        .drawer-credit {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            background: rgba(129,140,248,0.1);
            border: 1px solid rgba(129,140,248,0.2);
            color: var(--primary); padding: 0.6rem;
            border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        }
        @endauth

        /* Alert */
        .alert { padding: 0.875rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.875rem; }
        .alert-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #FCA5A5; }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #6EE7B7; }
        .alert-info { background: rgba(129,140,248,0.1); border: 1px solid rgba(129,140,248,0.3); color: var(--primary); }

        main { padding-top: 64px; min-height: calc(100vh - 64px); overflow-x: hidden; }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .nav-center { display: none; }
        }

        @media (max-width: 640px) {
            .main-nav { padding: 0 4%; }
            .nav-right .nav-credit { display: none; }
            .nav-right .btn-outline { display: none; }
            .nav-hamburger { display: flex; }
        }

        /* Light mode overrides */
        html.light .mobile-drawer { background: rgba(240, 244, 255, 0.98); }
        html.light .drawer-nav a:hover { background: rgba(99,102,241,0.08); }
        html.light body { background-image: linear-gradient(rgba(99,102,241,0.06) 1px,transparent 1px), linear-gradient(90deg,rgba(99,102,241,0.06) 1px,transparent 1px); }
    </style>
    @stack('styles')
</head>
<body>

{{-- Overlay --}}
<div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>

{{-- Mobile Drawer --}}
<div class="mobile-drawer" id="mobile-drawer">
    <div class="drawer-header">
        <a href="{{ route('home') }}" class="nav-brand" onclick="closeDrawer()">Try<span>On</span></a>
        <button class="drawer-close" onclick="closeDrawer()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="drawer-nav">
        <a href="{{ route('home') }}#nasil-calisir" onclick="closeDrawer()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            {{ __('how_it_works') }}
        </a>
        <a href="{{ route('home') }}#paketler" onclick="closeDrawer()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            {{ __('pricing') }}
        </a>
        <a href="#" onclick="closeDrawer()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            {{ __('api_docs') }}
        </a>

        <div class="drawer-divider"></div>

        @auth
        <a href="{{ route('dashboard') }}" onclick="closeDrawer()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            {{ __('nav_panel') }}
        </a>
        @endauth
    </nav>

    <div class="drawer-bottom">
        {{-- Kredi (auth) --}}
        @auth
        <div style="display:flex;align-items:center;justify-content:center;gap:0.5rem;background:rgba(129,140,248,0.1);border:1px solid rgba(129,140,248,0.2);color:var(--primary);padding:0.6rem;border-radius:10px;font-size:0.85rem;font-weight:600;">
            ✦ {{ Auth::user()->credit_balance }} {{ __('credits') }}
        </div>
        @endauth

        {{-- Dil + Tema --}}
        <div class="drawer-controls">
            <div class="lang-switcher">
                <a href="{{ route('lang.switch', ['locale' => 'tr', 'redirect' => url()->current()]) }}"
                   class="lang-btn {{ app()->getLocale() === 'tr' ? 'active' : '' }}">TR</a>
                <a href="{{ route('lang.switch', ['locale' => 'en', 'redirect' => url()->current()]) }}"
                   class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
            </div>
            <button class="theme-toggle" onclick="toggleTheme()">
                <svg id="d-icon-moon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <svg id="d-icon-sun" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/></svg>
            </button>
        </div>

        {{-- Auth butonları --}}
        <div class="drawer-actions">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center">{{ __('nav_logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline" onclick="closeDrawer()">{{ __('nav_login') }}</a>
                <a href="{{ route('register') }}" class="btn btn-primary" onclick="closeDrawer()">{{ __('nav_register') }}</a>
            @endauth
        </div>
    </div>
</div>

{{-- Main Nav --}}
<nav class="main-nav">
    <a href="{{ route('home') }}" class="nav-brand">Try<span>On</span></a>

    <div class="nav-center">
        <a href="{{ route('home') }}#nasil-calisir">{{ __('how_it_works') }}</a>
        <a href="{{ route('home') }}#paketler">{{ __('pricing') }}</a>
        <a href="#">{{ __('api_docs') }}</a>
    </div>

    <div class="nav-right">
        {{-- Desktop: dil + tema --}}
        <div class="lang-switcher" style="display:flex" id="desktop-lang">
            <a href="{{ route('lang.switch', ['locale' => 'tr', 'redirect' => url()->current()]) }}"
               class="lang-btn {{ app()->getLocale() === 'tr' ? 'active' : '' }}">TR</a>
            <a href="{{ route('lang.switch', ['locale' => 'en', 'redirect' => url()->current()]) }}"
               class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
        </div>

        <button class="theme-toggle" onclick="toggleTheme()">
            <svg id="icon-moon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:block"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg id="icon-sun" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/></svg>
        </button>

        @auth
            <div class="nav-credit">✦ {{ Auth::user()->credit_balance }} {{ __('credits') }}</div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">{{ __('nav_panel') }}</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">{{ __('nav_logout') }}</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline btn-sm">{{ __('nav_login') }}</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">{{ __('nav_register') }}</a>
        @endauth

        {{-- Hamburger --}}
        <button class="nav-hamburger" onclick="openDrawer()" id="hamburger-btn">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<main>
    @yield('content')
</main>

@stack('scripts')

<script>
    // ── Tema ──
    const root = document.getElementById('html-root');
    const icons = {
        moon: [document.getElementById('icon-moon'), document.getElementById('d-icon-moon')],
        sun:  [document.getElementById('icon-sun'),  document.getElementById('d-icon-sun')],
    };

    function applyTheme(theme) {
        if (theme === 'light') {
            root.classList.add('light');
            icons.moon.forEach(el => el && (el.style.display = 'none'));
            icons.sun.forEach(el => el && (el.style.display = 'block'));
        } else {
            root.classList.remove('light');
            icons.moon.forEach(el => el && (el.style.display = 'block'));
            icons.sun.forEach(el => el && (el.style.display = 'none'));
        }
    }

    function toggleTheme() {
        const next = (localStorage.getItem('theme') || 'dark') === 'dark' ? 'light' : 'dark';
        localStorage.setItem('theme', next);
        applyTheme(next);
    }

    applyTheme(localStorage.getItem('theme') || 'dark');

    // ── Drawer ──
    function openDrawer() {
        document.getElementById('mobile-drawer').classList.add('open');
        document.getElementById('drawer-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        document.getElementById('mobile-drawer').classList.remove('open');
        document.getElementById('drawer-overlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    // ESC ile kapat
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
</script>

</body>
</html>