@if ($paginator->hasPages())
    <nav class="pagination-nav" role="navigation" aria-label="Pagination">
        <div class="pagination-summary text-size-small text-color-body">
            Page {{ $paginator->currentPage() }} sur {{ $paginator->lastPage() }}
        </div>

        <div class="pagination-actions">
            @if ($paginator->onFirstPage())
                <span class="pagination-button is-disabled" aria-disabled="true">Precedent</span>
            @else
                <a class="pagination-button" href="{{ $paginator->previousPageUrl() }}" rel="prev">Precedent</a>
            @endif

            <div class="pagination-pages" aria-label="Pages">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="pagination-ellipsis" aria-hidden="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-page is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="pagination-page" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a class="pagination-button" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant</a>
            @else
                <span class="pagination-button is-disabled" aria-disabled="true">Suivant</span>
            @endif
        </div>
    </nav>
@endif