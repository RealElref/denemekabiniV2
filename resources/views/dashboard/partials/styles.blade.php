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
    

    /* --- LİSTE TAŞIYICISI --- */
.generations-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 15px 0;
}

/* --- LİSTE SATIRI (HER BİR ÖGE) --- */
.gen-list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    background-color: #1a1a1a; /* Koyu arka plan */
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative; /* İçindeki tooltip'in buna göre konumlanması için */
}

/* Satırın üzerine gelince (Hover) parlaması */
.gen-list-item:hover {
    border-color: #6366F1; /* Senin temanın primary rengi (mor/mavi) */
    background-color: rgba(99, 102, 241, 0.1);
}

/* --- SOL KISIM: YAZILAR VE BADGE --- */
.list-item-info {
    display: flex;
    align-items: center;
    gap: 15px;
    overflow: hidden; /* Uzun metinlerin taşmasını engeller */
}

.list-item-title {
    color: #e2e8f0;
    font-size: 0.95rem;
    white-space: nowrap; /* Metni tek satır yapar */
    overflow: hidden; /* Taşan kısmı gizler */
    text-overflow: ellipsis; /* Taşan kısmın sonuna '...' koyar */
    max-width: 300px; /* Yazının aşırı uzamasını engeller (mobilde daraltılabilir) */
}

/* --- SAĞ KISIM: GÖZ İKONU VE RESİM POP-UP --- */
.list-item-actions {
    display: flex;
    align-items: center;
}

.eye-icon-wrapper {
    position: relative;
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.eye-icon {
    font-size: 1.4rem;
    opacity: 0.6;
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.eye-icon-wrapper:hover .eye-icon {
    opacity: 1;
    transform: scale(1.1); /* Fare üstündeyken ikon hafif büyür */
}

/* --- GÖZ İKONUNUN ÜSTÜNE GELİNCE ÇIKAN RESİM (TOOLTIP) --- */
.hover-image-preview {
    position: absolute;
    /* Resmi ikonun solunda gösterir */
    right: 40px; 
    top: 50%;
    transform: translateY(-50%) scale(0.9);
    
    width: 180px;
    height: 180px;
    background-color: #000;
    border: 2px solid rgba(255,255,255,0.2);
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.8);
    overflow: hidden;
    z-index: 100;
    
    /* Başlangıçta gizli ve tıklanamaz */
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: all 0.2s cubic-bezier(0.32, 0.72, 0, 1); /* Pürüzsüz açılma animasyonu */
}

.hover-image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Resmi orantılı şekilde kareye sığdırır */
}

/* SİHİRLİ KISIM: Göz ikonuna gelince resmi göster */
.eye-icon-wrapper:hover .hover-image-preview {
    opacity: 1;
    visibility: visible;
    transform: translateY(-50%) scale(1);
}

/* MOBİL UYUM */
@media (max-width: 600px) {
    .list-item-title { max-width: 150px; }
    .hover-image-preview { width: 120px; height: 120px; right: 35px; }
}
</style>