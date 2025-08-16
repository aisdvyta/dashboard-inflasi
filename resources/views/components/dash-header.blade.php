{{-- resources/views/components/dash-header.blade.php --}}
@php
    // Helper untuk build URL route dengan param
    function dashHeaderBuildRoute($routeName, $tab, $routeParams) {
        $params = array_merge($routeParams ?? [], ['jenis_data_inflasi' => $tab]);
        return route($routeName, $params);
    }
@endphp
@props([
    'tabs' => ['ASEM 1', 'ASEM 2', 'ASEM 3', 'ATAP'],
    'activeTab' => null,
    'routeName' => null,
    'routeParams' => [],
    'exportExcelId' => null,
    'exportPngId' => null,
    'dropdownId' => 'exportDropdown',
    'dropdownLabel' => 'Export',
    'showDropdown' => true,
    'showExcel' => true,
    'showPng' => true,
    'excelLabel' => 'Export Excel',
    'pngLabel' => 'Export PNG',
    'excelIcon' => asset('images/excelIcon.svg'),
    'pngIcon' => asset('images/pngIcon.svg'),
    'extraClass' => '',
])
@php
    // Only show ATAP tab for guests
    if(auth()->guest()) {
        $tabs = ['ATAP'];
    }
    // Untuk user yang login (apapun rolenya), tampilkan semua tab
    $exportExcelId = $exportExcelId ?: 'exportExcel';
    $exportPngId = $exportPngId ?: 'exportPNG';
    // Tambahan: deteksi admin prov + ASEM
    $isBpsKabKot = auth()->check() && auth()->user()->id_role == 2;
    $isAdminProv = auth()->check() && auth()->user()->id_role == 1;
    $isAsem = isset(request()->jenis_data_inflasi) && in_array(request()->jenis_data_inflasi, ['ASEM 1','ASEM 2','ASEM 3']);
@endphp
<div class="flex flex-col md:flex-row items-center justify-between w-full {{ $extraClass }}">
    <div class="flex flex-row gap-0 mt-2 md:mt-0">
        @foreach($tabs as $tab)
            <a href="{{ dashHeaderBuildRoute($routeName, $tab, $routeParams) }}"
                class="tab-link flex items-center px-14 py-3 transition-all duration-300 rounded-t-xl {{ $activeTab === $tab ? 'bg-biru1 text-white' : 'bg-biru4 text-white' }} hover:bg-biru1 group"
                data-tab="{{ $tab }}" id="tab-{{ strtolower(str_replace(' ', '-', $tab)) }}">
                <span class="menu-text text-[15px] font-medium transition duration-100">
                    {{ $tab }}
                </span>
            </a>
        @endforeach
    </div>
    <div class="flex items-start gap-2 mt-2 md:mt-0">
        {{-- Sembunyikan tombol export jika admin prov + ASEM --}}
            @if($showDropdown)
            <div class="relative inline-block text-left">
                <button type="button" id="{{ $dropdownId }}" class="inline-flex justify-center w-full rounded-xl shadow-xl bg-biru4 px-4 py-2 text-sm font-medium text-white hover:bg-biru1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-biru1 transition duration-150 group">
                    <span>{{ $dropdownLabel }}</span>
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 hidden group-focus:block group-hover:block" id="{{ $dropdownId }}-menu">
                    <div class="py-1">
                        @if($showExcel)
                        <button id="{{ $exportExcelId }}" type="button" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="{{ $excelIcon }}" alt="Excel" class="h-5 w-5">{{ $excelLabel }}
                        </button>
                        @endif
                        @if($showPng)
                        <button id="{{ $exportPngId }}" type="button" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <img src="{{ $pngIcon }}" alt="PNG" class="h-5 w-5">{{ $pngLabel }}
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @else
                @if($showExcel)
                <button id="{{ $exportExcelId }}" class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-xl bg-hijau hover:bg-hijau2 text-white text-sm font-medium">
                    <img src="{{ $excelIcon }}" alt="Excel" class="h-5 w-5">{{ $excelLabel }}
                </button>
                @endif
                @if($showPng)
                <button id="{{ $exportPngId }}" class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-xl bg-merah1 hover:bg-merah1muda text-white text-sm font-medium">
                    <img src="{{ $pngIcon }}" alt="PNG" class="h-5 w-5">{{ $pngLabel }}
                </button>
                @endif
            @endif
    </div>
</div>
