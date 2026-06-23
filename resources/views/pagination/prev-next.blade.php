@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center py-3">
        @if ($paginator->onFirstPage())
            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                <i class="bi bi-chevron-left me-1"></i>Sebelumnya
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-chevron-left me-1"></i>Sebelumnya
            </a>
        @endif

        <span class="text-muted small">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
            ({{ $paginator->total() }} data)
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-primary btn-sm">
                Selanjutnya<i class="bi bi-chevron-right ms-1"></i>
            </a>
        @else
            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                Selanjutnya<i class="bi bi-chevron-right ms-1"></i>
            </button>
        @endif
    </div>
@endif
