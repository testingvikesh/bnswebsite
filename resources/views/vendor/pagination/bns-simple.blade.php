@if ($paginator->hasPages())
    <nav class="bns-pagination bns-pagination--simple" role="navigation" aria-label="Pagination">
        <ul class="bns-pagination__list">
            @if ($paginator->onFirstPage())
                <li class="bns-pagination__item is-disabled" aria-disabled="true">
                    <span class="bns-pagination__btn">@lang('pagination.previous')</span>
                </li>
            @else
                <li class="bns-pagination__item">
                    <a class="bns-pagination__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                </li>
            @endif

            @if ($paginator->hasMorePages())
                <li class="bns-pagination__item">
                    <a class="bns-pagination__btn" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                </li>
            @else
                <li class="bns-pagination__item is-disabled" aria-disabled="true">
                    <span class="bns-pagination__btn">@lang('pagination.next')</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
