<div id="embed-code-modal" onclick="if(event.target===this)closeEmbedModal()" style="display:none;position:fixed;inset:0;z-index:2200;align-items:center;justify-content:center;background:rgba(0,0,0,0.75);backdrop-filter:blur(6px)">
    <div style="background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:var(--radius-lg);padding:2rem;width:100%;max-width:560px;margin:1rem;position:relative;box-shadow:0 24px 64px rgba(0,0,0,0.55)">

        <button onclick="closeEmbedModal()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:var(--text-muted);font-size:1.5rem;cursor:pointer;line-height:1;padding:0.2rem 0.5rem;border-radius:6px;transition:var(--transition)" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='none'">&times;</button>

        <div style="margin-bottom:1.5rem">
            <h3 style="font-size:1.1rem;font-weight:700;color:var(--text-bright);margin:0 0 0.3rem" id="embed-modal-title">{{ __('embed_code') }}</h3>
            <p style="font-size:0.82rem;color:var(--text-muted);margin:0" id="embed-modal-domain"></p>
        </div>

        <div style="margin-bottom:1.25rem">
            <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-muted);margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em">{{ __('embed_script_tag') }}</label>
            <div style="position:relative">
                <pre id="embed-code-snippet" style="background:rgba(0,0,0,0.4);border:1px solid var(--glass-border);border-radius:8px;padding:0.85rem 1rem;font-family:monospace;font-size:0.78rem;color:#93c5fd;overflow-x:auto;white-space:pre-wrap;word-break:break-all;margin:0;line-height:1.6"></pre>
                <button onclick="copyEmbedCode()" style="position:absolute;top:0.5rem;right:0.5rem;background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);color:#3B82F6;padding:0.25rem 0.6rem;border-radius:6px;font-size:0.72rem;cursor:pointer;font-weight:600;transition:var(--transition)" id="embed-copy-btn">{{ __('copy') }}</button>
            </div>
        </div>

        <div style="background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15);border-radius:8px;padding:0.85rem 1rem;margin-bottom:1.5rem">
            <p style="font-size:0.8rem;color:var(--text-muted);margin:0;line-height:1.6">{{ __('embed_instructions') }}</p>
        </div>

        <div style="margin-bottom:1.25rem">
            <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-muted);margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em">{{ __('embed_manual_button') }}</label>
            <div style="position:relative">
                <pre id="embed-btn-snippet" style="background:rgba(0,0,0,0.4);border:1px solid var(--glass-border);border-radius:8px;padding:0.85rem 1rem;font-family:monospace;font-size:0.78rem;color:#6ee7b7;overflow-x:auto;white-space:pre-wrap;word-break:break-all;margin:0;line-height:1.6"></pre>
                <button onclick="copyEmbedBtn()" style="position:absolute;top:0.5rem;right:0.5rem;background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.25);color:#34D399;padding:0.25rem 0.6rem;border-radius:6px;font-size:0.72rem;cursor:pointer;font-weight:600;transition:var(--transition)" id="embed-btn-copy-btn">{{ __('copy') }}</button>
            </div>
        </div>

        <button onclick="closeEmbedModal()" style="width:100%;padding:0.7rem;background:rgba(255,255,255,0.05);border:1px solid var(--glass-border);border-radius:10px;color:var(--text-muted);font-size:0.85rem;cursor:pointer;font-weight:600;transition:var(--transition)" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
            {{ __('close') }}
        </button>
    </div>
</div>
