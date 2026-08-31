@if ($paginator->hasPages())
    <nav class="bns-pagination" role="navigation" aria-label="Pagination">
        <p class="bns-pagination__meta">
            Showing
            <strong>{{ $paginator->firstItem() }}</strong>
            to
            <strong>{{ $paginator->lastItem() }}</strong>
            of
            <strong>{{ $paginator->total() }}</strong>
            results
        </p>

        <ul class="bns-pagination__list">
            @if ($paginator->onFirstPage())
                <li class="bns-pagination__item is-disabled" aria-disabled="true">
                    <span class="bns-pagination__btn" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                </li>
            @else
                <li class="bns-pagination__item">
                    <a class="bns-pagination__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="bns-pagination__item is-disabled" aria-disabled="true">
                        <span class="bns-pagination__btn bns-pagination__btn--dots">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="bns-pagination__item is-active" aria-current="page">
                                <span class="bns-pagination__btn">{{ $page }}</span>
                            </li>
                        @else
                            <li class="bns-pagination__item">
                                <a class="bns-pagination__btn" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="bns-pagination__item">
                    <a class="bns-pagination__btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="bns-pagination__item is-disabled" aria-disabled="true">
                    <span class="bns-pagination__btn" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
