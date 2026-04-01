<div id="domain-modal" onclick="if(event.target===this)closeDomainModal()" style="display:none;position:fixed;inset:0;z-index:2100;align-items:center;justify-content:center;background:rgba(0,0,0,0.7);backdrop-filter:blur(6px)">
    <div style="background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:var(--radius-lg);padding:2rem;width:100%;max-width:480px;margin:1rem;position:relative;box-shadow:0 24px 64px rgba(0,0,0,0.5)">

        <button onclick="closeDomainModal()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:var(--text-muted);font-size:1.5rem;cursor:pointer;line-height:1;padding:0.2rem 0.5rem;border-radius:6px;transition:var(--transition)" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='none'">&times;</button>

        <div style="margin-bottom:1.5rem">
            <h3 style="font-size:1.15rem;font-weight:700;color:var(--text-bright);margin:0 0 0.3rem">{{ __('add_domain_title') }}</h3>
            <p style="font-size:0.82rem;color:var(--text-muted);margin:0">{{ __('add_domain_sub') }}</p>
        </div>

        <div id="domain-modal-error" style="display:none;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:0.75rem 1rem;font-size:0.82rem;color:#EF4444;margin-bottom:1rem"></div>
        <div id="domain-modal-success" style="display:none;background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.3);border-radius:8px;padding:0.75rem 1rem;font-size:0.82rem;color:#34D399;margin-bottom:1rem"></div>

        <form id="domain-form" onsubmit="submitDomain(event)">
            @csrf
            <div style="margin-bottom:1rem">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em">{{ __('domain_input_label') }}</label>
                <div style="display:flex;gap:0.5rem;align-items:stretch">
                    <input
                        type="text"
                        id="domain-name-input"
                        placeholder="{{ __('domain_input_placeholder') }}"
                        maxlength="63"
                        style="flex:1;background:rgba(0,0,0,0.3);border:1px solid var(--glass-border);border-radius:8px;padding:0.6rem 0.85rem;color:var(--text-bright);font-size:0.9rem;font-family:monospace;outline:none;transition:var(--transition)"
                        onfocus="this.style.borderColor='var(--primary)'"
                        onblur="this.style.borderColor='var(--glass-border)'"
                    >
                    <input
                        type="text"
                        id="domain-tld-input"
                        list="tld-suggestions"
                        value=".com"
                        placeholder=".com"
                        autocomplete="off"
                        spellcheck="false"
                        style="width:110px;flex:none;background:rgba(0,0,0,0.4);border:1px solid var(--glass-border);border-radius:8px;padding:0.6rem 0.75rem;color:var(--text-bright);font-size:0.85rem;font-family:monospace;outline:none;transition:var(--transition)"
                        onfocus="this.style.borderColor='var(--primary)'"
                        onblur="this.style.borderColor='var(--glass-border)'"
                    >
                    <datalist id="tld-suggestions">
                        <option value=".com">
                        <option value=".net">
                        <option value=".org">
                        <option value=".io">
                        <option value=".co">
                        <option value=".app">
                        <option value=".dev">
                        <option value=".site">
                        <option value=".store">
                        <option value=".shop">
                        <option value=".space">
                        <option value=".online">
                        <option value=".tech">
                        <option value=".info">
                        <option value=".biz">
                        <option value=".ai">
                        <option value=".me">
                        <option value=".pro">
                        <option value=".com.tr">
                        <option value=".net.tr">
                        <option value=".org.tr">
                        <option value=".tr">
                    </datalist>
                </div>
                <p id="domain-preview" style="font-size:0.78rem;color:var(--text-muted);margin:0.4rem 0 0;font-family:monospace"></p>
            </div>

            <div style="margin-bottom:1.5rem">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.05em">{{ __('domain_years_label') }}</label>
                <div style="display:flex;gap:0.5rem">
                    @foreach([1,2,3,5] as $yr)
                    <label style="flex:1;cursor:pointer">
                        <input type="radio" name="registration_years" value="{{ $yr }}" {{ $yr === 1 ? 'checked' : '' }} style="display:none" onchange="updateYearSelect()">
                        <div class="year-btn" data-year="{{ $yr }}" onclick="selectYear({{ $yr }})" style="text-align:center;padding:0.5rem;border:1px solid var(--glass-border);border-radius:8px;font-size:0.82rem;color:var(--text-muted);transition:var(--transition);user-select:none" onmouseover="if(this.dataset.active!='1')this.style.borderColor='var(--glass-border-hover)'" onmouseout="if(this.dataset.active!='1')this.style.borderColor='var(--glass-border)'">
                            {{ $yr }} {{ __('year') }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;gap:0.75rem">
                <button type="button" onclick="closeDomainModal()" style="flex:1;padding:0.7rem;background:rgba(255,255,255,0.05);border:1px solid var(--glass-border);border-radius:10px;color:var(--text-muted);font-size:0.85rem;cursor:pointer;font-weight:600;transition:var(--transition)" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                    {{ __('cancel') }}
                </button>
                <button type="submit" id="domain-submit-btn" style="flex:2;padding:0.7rem;background:linear-gradient(135deg,#3B82F6,#1D4ED8);border:none;border-radius:10px;color:#fff;font-size:0.88rem;cursor:pointer;font-weight:700;transition:var(--transition);box-shadow:0 4px 15px rgba(59,130,246,0.3)">
                    {{ __('domain_submit') }}
                </button>
            </div>
        </form>
    </div>
</div>
