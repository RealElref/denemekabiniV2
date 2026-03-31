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
                    <a href="javascript:void(0)" onclick="loadPage('{{ $recentTransactions->appends(request()->except('tx_page'))->previousPageUrl() }}', 'tab-transactions', 'tab-transactions')" class="page-btn">{{ __('show_prev') }}</a>
                @endif
                @if($recentTransactions->hasMorePages())
                    <a href="javascript:void(0)" onclick="loadPage('{{ $recentTransactions->appends(request()->except('tx_page'))->nextPageUrl() }}', 'tab-transactions', 'tab-transactions')" class="page-btn">{{ __('show_more') }}</a>
                @endif
            </div>
        </div>
        @endif
    @endif
</div>