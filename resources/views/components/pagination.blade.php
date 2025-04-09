@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 text-gray-500 bg-gray-200 rounded">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-blue-500 bg-gray-100 rounded hover:bg-gray-200">Previous</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-4 py-2 text-gray-500">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2 text-white bg-blue-500 rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 text-blue-500 bg-gray-100 rounded hover:bg-gray-200">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-blue-500 bg-gray-100 rounded hover:bg-gray-200">Next</a>
        @else
            <span class="px-4 py-2 text-gray-500 bg-gray-200 rounded">Next</span>
        @endif
    </nav>
@endif