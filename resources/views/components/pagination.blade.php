@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between pt-4 pb-2">
        <div class="pl-6">
            @if ($paginator->onFirstPage())
                <span class="font-semibold text-biru5 flex items-center space-x-1">
                    <span>&laquo;</span>
                    <span>Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="font-semibold text-biru4 hover:text-biru1 flex items-center space-x-1">
                    <span>&laquo;</span>
                    <span>Sebelumnya</span>
                </a>
            @endif
        </div>

        <div class="flex space-x-2 items-center">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-gray-500">…</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center text-white bg-biru1 rounded-md font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center text-biru1 bg-gray-100 rounded-md hover:bg-gray-200">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        <div class="pr-6">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="font-semibold text-biru4 hover:text-biru1 flex items-center space-x-1">
                    <span>Selanjutnya</span>
                    <span>&raquo;</span>
                </a>
            @else
                <span class="font-semibold text-biru5 flex items-center space-x-1">
                    <span>Selanjutnya</span>
                    <span>&raquo;</span>
                </span>
            @endif
        </div>
    </nav>
@endif
