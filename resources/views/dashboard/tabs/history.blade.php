<div id="tab-history" class="tab-content active">
    @if($recentGenerations->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">✨</div>
            <p>{{ __('no_generations') }}</p>
        </div>
    @else
        <div class="list-controls">
       <select id="perPageSelect" class="custom-select" onchange="changePerPage(this.value)">
    <option value="3" {{ request('per_page', 3) == 3 ? 'selected' : '' }}>3</option>
    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
</select>
        </div>

        <div class="generations-list">
            @foreach($recentGenerations as $gen)
            <div class="gen-list-item" 
                 data-image="{{ Storage::url($gen->result_image_path) }}" 
                 data-desc="{{ $gen->prompt ?? 'Bu görsel için bir açıklama bulunmuyor.' }}"
                 onclick="sendToIndex(this)">
                
                <div class="list-item-info">
                    <span class="list-item-title">{{ $gen->prompt ?? 'İsimsiz Görsel' }}</span>
                    <span class="badge badge-{{ $gen->status === 'completed' ? 'success' : ($gen->status === 'failed' ? 'danger' : 'warning') }}">
                        {{ $gen->status_label }}
                    </span>
                </div>

                <div class="list-item-actions">
                    @if($gen->result_image_path)
                        <div class="eye-icon-wrapper">
                            <span class="eye-icon">👁️</span>
                            <div class="hover-image-preview">
                                <img src="{{ Storage::url($gen->result_image_path) }}" alt="Preview">
                            </div>
                        </div>
                    @else
                        <span class="processing-text" style="font-size: 0.8rem;">{{ __('processing') }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        @if($recentGenerations->hasPages())
        <div class="pagination-wrap">
            <span class="pagination-info">{{ $recentGenerations->firstItem() }}–{{ $recentGenerations->lastItem() }} / {{ $recentGenerations->total() }}</span>
            <div class="pagination-btns">
                @if(!$recentGenerations->onFirstPage())
                    <a href="javascript:void(0)" onclick="loadPage('{{ $recentGenerations->appends(request()->except('gen_page'))->previousPageUrl() }}', 'tab-history', 'tab-history')" class="page-btn">{{ __('show_prev') }}</a>
                @endif
                @if($recentGenerations->hasMorePages())
                    <a href="javascript:void(0)" onclick="loadPage('{{ $recentGenerations->appends(request()->except('gen_page'))->nextPageUrl() }}', 'tab-history', 'tab-history')" class="page-btn">{{ __('show_more') }}</a>
                @endif
            </div>
        </div>
        @endif
    @endif
</div>

<style>
.list-controls {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    padding: 0 5px;
}

.custom-select {
    background-color: rgba(2, 6, 23, 0.6);
    color: #e2e8f0;
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    padding: 6px 30px 6px 12px;
    font-size: 0.85rem;
    font-family: inherit;
    font-weight: 600;
    outline: none;
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 14px;
}

.custom-select:hover,
.custom-select:focus {
    border-color: var(--primary);
    background-color: rgba(2, 6, 23, 0.8);
}

.custom-select option {
    background-color: #1a1a1a;
    color: #fff;
}
</style>