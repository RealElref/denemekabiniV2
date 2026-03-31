@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div style="font-size:0.8rem;color:var(--text-muted)">
        {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} / {{ $paginator->total() }}
    </div>
    <div style="display:flex;gap:0.5rem">
        @if ($paginator->onFirstPage())
            <span style="padding:0.4rem 0.9rem;border-radius:99px;border:1px solid var(--glass-border);color:var(--text-muted);font-size:0.8rem;cursor:not-allowed;opacity:0.5">← {{ __('pagination.previous') }}</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding:0.4rem 0.9rem;border-radius:99px;border:1px solid var(--glass-border);color:var(--text-bright);font-size:0.8rem;text-decoration:none;background:var(--glass-bg);transition:all 0.2s" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--glass-border)'">← {{ __('pagination.previous') }}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding:0.4rem 0.9rem;border-radius:99px;border:1px solid var(--glass-border);color:var(--text-bright);font-size:0.8rem;text-decoration:none;background:var(--glass-bg);transition:all 0.2s" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--glass-border)'">{{ __('pagination.next') }} →</a>
        @else
            <span style="padding:0.4rem 0.9rem;border-radius:99px;border:1px solid var(--glass-border);color:var(--text-muted);font-size:0.8rem;cursor:not-allowed;opacity:0.5">{{ __('pagination.next') }} →</span>
        @endif
    </div>
</nav>
@endif