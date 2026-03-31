<div id="tab-domains" class="tab-content">
    <div style="margin-bottom:1rem;display:flex;justify-content:space-between;align-items:center">
        <p style="color:var(--text-muted);font-size:0.85rem;margin:0">{{ __('domains_tab_desc') }}</p>
        <button class="btn btn-primary" onclick="openDomainModal()" style="padding:0.5rem 1.2rem;font-size:0.82rem">
            {{ __('add_domain') }}
        </button>
    </div>

    @if($domains->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🌐</div>
            <p>{{ __('no_domains') }}</p>
            <button class="btn btn-outline" onclick="openDomainModal()" style="margin-top:0.75rem;font-size:0.82rem">
                {{ __('add_domain') }}
            </button>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('domain_name') }}</th>
                        <th>{{ __('domain_status') }}</th>
                        <th>{{ __('domain_expires') }}</th>
                        <th>{{ __('domain_api_key') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domains as $domain)
                    <tr>
                        <td style="font-family:monospace;color:var(--text-bright);font-weight:600">{{ $domain->full_domain }}</td>
                        <td>
                            <span class="badge badge-{{ $domain->status === 'active' ? 'success' : ($domain->status === 'pending' ? 'warning' : ($domain->status === 'rejected' ? 'danger' : 'gray')) }}">
                                {{ $domain->status_label }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:0.82rem">{{ $domain->expires_at?->format('d.m.Y') ?? __('domain_unlimited') }}</td>
                        <td>
                            @if($domain->api_key)
                                <code onclick="copyDomainKey(this)" title="{{ __('domain_copy_key') }}" data-key="{{ $domain->api_key }}" style="cursor:pointer;font-size:0.75rem;padding:0.2rem 0.5rem;background:rgba(0,0,0,0.3);border-radius:6px;border:1px solid var(--glass-border);color:var(--text-muted);transition:var(--transition)">{{ substr($domain->api_key, 0, 12) }}…</code>
                            @else
                                <span style="color:var(--text-muted);font-size:0.78rem">—</span>
                            @endif
                        </td>
                        <td style="text-align:right">
                            @if(in_array($domain->status, ['pending', 'rejected']))
                                <button onclick="deleteDomain({{ $domain->id }}, this)" style="background:none;border:1px solid rgba(239,68,68,0.3);color:#EF4444;padding:0.25rem 0.6rem;border-radius:6px;font-size:0.75rem;cursor:pointer;transition:var(--transition)" onmouseover="this.style.background='rgba(239,68,68,0.1)'" onmouseout="this.style.background='none'">
                                    {{ __('delete') }}
                                </button>
                            @endif
                        </td>
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
                    <a href="javascript:void(0)" onclick="loadPage('{{ $domains->appends(request()->except('domain_page'))->previousPageUrl() }}', 'tab-domains', 'tab-domains')" class="page-btn">{{ __('show_prev') }}</a>
                @endif
                @if($domains->hasMorePages())
                    <a href="javascript:void(0)" onclick="loadPage('{{ $domains->appends(request()->except('domain_page'))->nextPageUrl() }}', 'tab-domains', 'tab-domains')" class="page-btn">{{ __('show_more') }}</a>
                @endif
            </div>
        </div>
        @endif
    @endif
</div>
