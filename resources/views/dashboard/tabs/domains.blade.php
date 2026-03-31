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