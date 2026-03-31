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