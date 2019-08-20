@if ($paginator->hasPages())
    <div class="pagination shadow" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="disabled pagination__link pagination__link--prev" aria-hidden="true" aria-label="@lang('pagination.previous')"><i class="fal fa-chevron-left"></i> Prev</span>
        @else
            <a class="pagination__link pagination__link--prev" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><i class="fal fa-chevron-left"></i> Prev</a>
        @endif
        <div class="pagination__pages">
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
            <ol>
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            </ol>
            @endforeach
         </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="pagination__link pagination__link--next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next <i class="fal fa-chevron-right"></i></a>
        @else
            <span class="disabled pagination__link pagination__link--next" aria-hidden="true" aria-label="@lang('pagination.next')">Next <i class="fal fa-chevron-right"></i></span>
        @endif
    </div>
@endif