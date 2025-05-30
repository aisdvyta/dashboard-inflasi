@extends('layouts.dashboard')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .map-container {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        #map {
            width: 100%;
            height: 100%;
            /* Kotak */
            max-width: 100%;
        }

        .label-tooltip {
            background-color: rgba(0, 123, 255, 0.8);
            border: 2px solid #007bff;
            padding: 5px 10px;
            font-size: 14px;
            color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
    </style>
@endpush

@php
    function getHeatClass($value, $min, $max, $top)
    {
        $isPositive = $top > 0;

        if ($isPositive) {
            $percentage = $max - $min != 0 ? ($value - $min) / ($max - $min) : 0;
            if ($percentage >= 0.8) {
                return 'bg-biru2 text-white';
            } elseif ($percentage >= 0.6) {
                return 'bg-biru3 text-white';
            } elseif ($percentage >= 0.4) {
                return 'bg-biru4 text-white';
            } elseif ($percentage >= 0.2) {
                return 'bg-biru5    ';
            } else {
                return 'bg-white';
            }
        } else {
            $percentage = $min - $max != 0 ? ($value - $max) / ($min - $max) : 0;
            if ($percentage >= 0.8) {
                return 'bg-biru2 text-white';
            } elseif ($percentage >= 0.6) {
                return 'bg-biru3 text-white';
            } elseif ($percentage >= 0.4) {
                return 'bg-biru4 text-white';
            } elseif ($percentage >= 0.2) {
                return 'bg-biru5';
            } else {
                return 'bg-white';
            }
        }
    }

    $minInflasiMtM = $topInflasiMtM->min('inflasi');
    $maxInflasiMtM = $topInflasiMtM->max('inflasi');
    $minInflasiYtD = $topInflasiYtD->min('inflasi');
    $maxInflasiYtD = $topInflasiYtD->max('inflasi');
    $minInflasiYoY = $topInflasiYoY->min('inflasi');
    $maxInflasiYoY = $topInflasiYoY->max('inflasi');
    $minAndilMtM = $topInflasiMtM->min('andil');
    $maxAndilMtM = $topInflasiMtM->max('andil');
    $minAndilYtD = $topInflasiYtD->min('andil');
    $maxAndilYtD = $topInflasiYtD->max('andil');
    $minAndilYoY = $topInflasiYoY->min('andil');
    $maxAndilYoY = $topInflasiYoY->max('andil');

    // Add condition for black and white mode
    $isBlackWhite = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
@endphp

@section('body')
    <div class="container mx-auto">
        {{-- Header --}}
        <div class="px-4 py-4 ">
            <p class="text-[2.5rem] font-bold text-biru1">
                <span class="text-kuning1">Jenis</span> Data Inflasi
            </p>
        </div>

        <div class="flex flex-col items-center justify-between md:flex-row ">
            <div class="relative flex justify-start mt-7">
                @php
                    $tabs = ['ASEM 1', 'ASEM 2', 'ASEM 3', 'ATAP'];
                @endphp
                @foreach ($tabs as $tab)
                    <a href="{{ route('dashboard.spasial', ['jenis_data_inflasi' => $tab]) }}"
                        class="tab-link flex items-center px-14 py-2 transition-all duration-300 rounded-t-xl {{ $jenisDataInflasi === $tab ? 'bg-biru1 text-white' : 'bg-biru4 text-white' }} hover:bg-biru1 group"
                        data-tab="{{ $tab }}" id="tab-{{ strtolower(str_replace(' ', '-', $tab)) }}">
                        <span class="menu-text text-[15px] font-medium transition duration-100">
                            {{ $tab }}
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="flex items-start gap-2 ">
                <button id="exportExcel"
                    class="flex items-start gap-2 pl-2 pr-5 py-2 transition duration-300 shadow-xl rounded-xl bg-hijau hover:bg-hijau2 hover:-translate-y-1 group">
                    <img src="{{ asset('images/excelIcon.svg') }}" alt="Ikon Eksport Excel" class="h-6 w-6 icon">
                    <span class="menu-text text-white text-[15px] transition duration-100">
                        Export Excel</span>
                </button>
                <button id="exportPdf"
                    class="flex items-end gap-2 pl-2 pr-5 py-2 transition duration-300 shadow-xl rounded-xl bg-merah1 hover:bg-merah1muda hover:-translate-y-1 group">
                    <img src="{{ asset('images/pdfIcon.svg') }}" alt="Ikon Eksport PDF" class="h-6 w-6 icon">
                    <span class="menu-text text-white text-[15px] transition duration-100">
                        Export PDF</span>
                </button>
            </div>
        </div>
    </div>

    <div class="border-t-8 border-biru1">
        <div class="p-6 bg-white rounded-b-xl shadow-md {{ $isBlackWhite ? 'grayscale' : '' }}">
            <!-- === JUDUL === -->
            <div class="grid pl-4 items-end grid-cols-1 gap-6 md:grid-cols-2 space-y-10">
                {{-- judul --}}
                <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <h1 class="text-5xl font-bold md:text-5xl text-biru1">Dashboard</h1>
                    <h1 class="text-5xl font-bold md:text-5xl text-biru4">INFLASI BULANAN</h1>
                    <h1 class="text-5xl font-bold text-biru1">Provinsi Jawa Timur</h1>
                    <div class=" gap-4 pt-2 text-5xl leading-8 text-biru1 pr-36">
                        <span class="text-right">{{ $bulan }}</span>
                        <span class="text-right">{{ $tahun }}</span>
                    </div>
                </div>

                {{-- Filter --}}
                <form method="GET" action="{{ route('dashboard.spasial') }}" class="flex flex-col gap-4 items-start">
                    <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                    {{-- Filter Bulan dan Tahun --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full pl-24 pr-4 pt-2 mx-auto max-w-7xl">
                        {{-- Bulan --}}
                        <div class="relative">
                            <select id="bulan" name="bulan"
                                class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option value="">Pilih Bulan</option>
                                @foreach ($daftarPeriode->pluck('bulan')->unique() as $bulanOption)
                                    <option value="{{ $bulanOption }}" {{ $bulanOption == $bulan ? 'selected' : '' }}>
                                        {{ $bulanOption }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-white transition-transform duration-200 rotate-180" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        {{-- Tahun --}}
                        <div class="relative">
                            <select id="tahun" name="tahun"
                                class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option value="">Pilih Tahun</option>
                                @foreach ($daftarPeriode->pluck('tahun')->unique() as $tahunOption)
                                    <option value="{{ $tahunOption }}" {{ $tahunOption == $tahun ? 'selected' : '' }}>
                                        {{ $tahunOption }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-white transition-transform duration-200 rotate-180" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        {{-- Tombol Submit --}}
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 w-full pl-24 pr-4 pb-2 mx-auto max-w-7xl">
                        <div class="flex justify-start w-full">
                            <button type="submit"
                                class="w-full px-6 py-2 font-semibold text-white bg-orange-500 rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel dan chloroplet --}}
            <div class="w-full px-4 py-10 mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                <div class="grid grid-cols-4 gap-4">
                    <!-- Tabel: 1/4 layar -->
                    <div class="col-span-1 h-auto rounded-2xl">
                        <div class="shadow-md sm:rounded-lg">
                            <table class="w-full p-2 mx-auto text-sm text-left rounded-t-lg rtl:text-right">
                                <thead class="text-xs text-white bg-biru1">
                                    <tr>
                                        <th scope="col" class="px-2 py-2 text-left">Kabupaten/Kota</th>
                                        <th scope="col" class="px-2 py-2 text-right">Andil MtM</th>
                                        <th scope="col" class="px-2 py-2 text-right">Inflasi MtM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topInflasiMtM as $index => $item)
                                        <tr class="text-xs text-biru1">
                                            <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                            <td
                                                class="px-2 py-2 text-right {{ getHeatClass($item->andil, $minAndilMtM, $maxAndilMtM, $inflasiMtM) }}">
                                                {{ number_format($item->andil, 2, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-2 py-2 text-right {{ getHeatClass($item->inflasi, $minInflasiMtM, $maxInflasiMtM, $inflasiMtM) }}">
                                                {{ number_format($item->inflasi, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Chloroplet: 3/4 layar -->
                    <div class="col-span-3">
                        <div class="map-container h-96 rounded-2xl shadow-md">
                            <div id="map" class="w-full h-full rounded-xl shadow-md"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Komoditas --}}
            <div class="grid grid-cols-2 gap-6 w-full px-4 py-2 mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                <!-- === JUDUL === -->
                <div>
                    <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                        <h1 class="text-5xl font-bold md:text-5xl text-biru1">Dashboard</h1>
                        <h1 class="text-5xl font-bold md:text-5xl text-biru4">INFLASI BULANAN</h1>
                        <h1 class="text-5xl font-bold text-biru1">Provinsi Jawa Timur</h1>
                        <div class=" gap-4 pt-2 text-5xl leading-8 text-biru1 pr-36">
                            <span class="text-right">{{ $bulan }}</span>
                            <span class="text-right">{{ $tahun }}</span>
                        </div>
                    </div>
                </div>
                <!-- === TABEL KOMODITAS === -->
                <div class="h-auto rounded-2xl">
                    <div class="shadow-md sm:rounded-lg">
                        <table class="w-full mx-auto text-sm text-left rtl:text-right">
                            <thead class="text-xs text-white bg-biru1">
                                <tr>
                                    <th scope="col" class="px-2 py-2 text-left">Kabupaten/Kota</th>
                                    <th scope="col" class="px-2 py-2 text-right">Andil MtM</th>
                                    <th scope="col" class="px-2 py-2 text-right">Inflasi MtM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topInflasiMtM as $index => $item)
                                    <tr class="text-xs text-biru1">
                                        <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                        <td
                                            class="px-2 py-2 text-right {{ getHeatClass($item->andil, $minAndilMtM, $maxAndilMtM, $inflasiMtM) }}">
                                            {{ number_format($item->andil, 2, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-2 py-2 text-right {{ getHeatClass($item->inflasi, $minInflasiMtM, $maxInflasiMtM, $inflasiMtM) }}">
                                            {{ number_format($item->inflasi, 2, ',', '.') }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ini buat dash spasialnya --}}
            <div>
                <div class="w-full px-6 py-10 mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <div class="grid items-start grid-cols-1 gap-6 md:grid-cols-2 {{ $isBlackWhite ? 'grayscale' : '' }}">
                        {{-- judul --}}
                        <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                            <h1 class="text-5xl font-bold md:text-5xl text-biru1">Dashboard</h1>
                            <h1 class="text-5xl font-bold md:text-5xl text-biru4">INFLASI BULANAN</h1>
                            <h1 class="text-5xl font-bold text-biru1">Provinsi Jawa Timur</h1>
                            <div class="flex justify-end gap-4 pt-2 text-5xl leading-8 text-biru1 pr-36">
                                <span class="text-right">{{ $bulan }}</span>
                                <span class="text-right">{{ $tahun }}</span>
                            </div>
                        </div>

                        {{-- pojok kanan atas --}}
                        <div class="{{ $isBlackWhite ? 'grayscale' : '' }}">
                            <!-- Filter -->
                            <div class="flex justify-end gap-2">
                                {{-- Filter --}}
                                <form method="GET" action="{{ route('dashboard.spasial') }}"
                                    class="flex flex-col gap-4 items-start">
                                    <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">

                                    {{-- Filter Kab/Kota --}}
                                    <div class="relative w-72">
                                        <select id="kabkota" name="kabkota"
                                            class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <option value="">Pilih Kab/Kota</option>
                                            {{-- @foreach ($daftarKabKota as $kabkotaOption)
                                            <option value="{{ $kabkotaOption }}"
                                                {{ $kabkotaOption == $kabkota ? 'selected' : '' }}>
                                                {{ $kabkotaOption }}
                                            </option>
                                        @endforeach --}}
                                        </select>
                                        <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Tombol Submit --}}
                                    <div class="flex justify-start w-72">
                                        <button type="submit"
                                            class="w-full px-6 py-2 font-semibold text-white bg-orange-500 rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                                            Filter
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-2">
                    <div class="flex items-stretch col-span-3 gap-4">
                        {{-- MtM --}}
                        <div class="flex flex-col flex-1 gap-2">
                            <div class="text-xs italic leading-tight text-white">
                                ?<br>
                                <span class="text-biru4">Nilai inflasi pada Bulan saat ini terhadap Bulan sebelumnya</span>
                            </div>
                            <div
                                class="flex flex-col justify-between flex-1 h-full px-4 py-2 text-white shadow-lg bg-biru1 rounded-2xl">
                                <div class="flex items-end justify-between">
                                    <div class="text-sm font-bold">
                                        Nilai Inflasi Bulanan<br>
                                        <span class="text-xs italic font-normal">(M-to-M, %)</span>
                                    </div>
                                    <div id="inflasiMtM"
                                        class="text-5xl font-semibold {{ $inflasiMtM < 0 ? 'text-hijau' : 'text-merah1' }} {{ $isBlackWhite ? 'text-white' : '' }}">
                                        {{ number_format($inflasiMtM, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- YtD --}}
                        <div class="flex flex-col flex-1 gap-2">
                            <div class="text-xs italic leading-tight text-biru4">
                                Nilai inflasi pada Bulan saat ini terhadap Bulan Desember Tahun sebelumnya
                            </div>
                            <div
                                class="flex flex-col justify-between flex-1 h-full px-4 py-2 text-white shadow-lg bg-biru1 rounded-2xl">
                                <div class="flex items-end justify-between">
                                    <div class="text-sm font-bold">
                                        Nilai Inflasi Tahun Kalender<br>
                                        <span class="text-xs italic font-normal">(Y-to-D, %)</span>
                                    </div>
                                    <div id="inflasiYtD"
                                        class="text-5xl font-semibold {{ $inflasiYtD < 0 ? 'text-hijau' : 'text-merah1' }} {{ $isBlackWhite ? 'text-white' : '' }}">
                                        {{ number_format($inflasiYtD, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- YoY --}}
                        <div class="flex flex-col flex-1 gap-2">
                            <div class="text-xs italic leading-tight text-biru4">
                                Nilai inflasi pada Bulan ini di Tahun saat ini terhadap Bulan ini di Tahun sebelumnya
                            </div>
                            <div
                                class="flex flex-col justify-between flex-1 h-full px-4 py-2 text-white shadow-lg bg-biru1 rounded-2xl">
                                <div class="flex items-end justify-between">
                                    <div class="text-sm font-bold">
                                        Nilai Inflasi Tahunan<br>
                                        <span class="text-xs italic font-normal">(Y-to-Y, %)</span>
                                    </div>
                                    <div id="inflasiYoY"
                                        class="text-5xl font-semibold {{ $inflasiYoY < 0 ? 'text-hijau' : 'text-merah1' }} {{ $isBlackWhite ? 'text-white' : '' }}">
                                        {{ number_format($inflasiYoY, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- js echart barchart --}}
                <div class="grid grid-cols-3 gap-4 mt-4 ">
                    <div class="p-3 bg-white border shadow-lg rounded-2xl border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas dengan <br> Sumbangan Inflasi Bulanan Terbesar<br>
                            <span class="italic font-normal">(M-to-M, %)</span>
                        </h2>
                        <div class="h-80" id="andilmtm"></div>
                    </div>

                    <div class="p-3 bg-white border shadow-lg rounded-2xl border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas dengan <br> Sumbangan Inflasi Tahun Kalender Terbesar<br>
                            <span class="italic font-normal">(Y-to-D, %)</span>
                        </h2>
                        <div class="h-80" id="andilytd"></div>
                    </div>

                    <div class="p-3 bg-white border shadow-lg rounded-2xl border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas dengan <br> Sumbangan Inflasi Tahunan Terbesar<br>
                            <span class="italic font-normal">(Y-on-Y, %)</span>
                        </h2>
                        <div class="h-80" id="andilyoy"></div>
                    </div>
                </div>

                {{-- tabel top komoditas --}}
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <div class="h-auto p-4 bg-white border shadow-lg rounded-2xl border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas Penyumbang Inflasi Bulanan<br>
                            <span class="italic font-normal">(M-to-M, %)</span>
                        </h2>
                        <div class="shadow-md sm:rounded-lg">
                            <table class="w-full mx-auto text-sm text-left rtl:text-right">
                                <thead class="text-xs text-white bg-biru1">
                                    <tr>
                                        <th scope="col" class="px-2 py-2 text-left"> </th>
                                        <th scope="col" class="px-2 py-2 text-left">Nama Komoditas</th>
                                        <th scope="col" class="px-2 py-2 text-right">Inflasi MtM</th>
                                        <th scope="col" class="px-2 py-2 text-right">Andil MtM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topInflasiMtM as $index => $item)
                                        <tr class="text-xs text-biru1">
                                            <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                            <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                            <td
                                                class="px-2 py-2 text-right {{ getHeatClass($item->inflasi, $minInflasiMtM, $maxInflasiMtM, $inflasiMtM) }}">
                                                {{ number_format($item->inflasi, 2, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-2 py-2 text-right {{ getHeatClass($item->andil, $minAndilMtM, $maxAndilMtM, $inflasiMtM) }}">
                                                {{ number_format($item->andil, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="h-auto p-4 bg-white border shadow-lg rounded-2xl border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas Penyumbang Inflasi Tahun Kalender<br>
                            <span class="italic font-normal">(Y-to-D, %)</span>
                        </h2>
                        <div class="shadow-md sm:rounded-lg">
                            <table class="w-full mx-auto text-sm text-left rtl:text-right">
                                <thead class="text-xs text-white bg-biru1">
                                    <tr>
                                        <th scope="col" class="px-2 py-2 text-left"> </th>
                                        <th scope="col" class="px-2 py-2 text-left">Nama Komoditas</th>
                                        <th scope="col" class="px-2 py-2 text-right">Inflasi YtD</th>
                                        <th scope="col" class="px-2 py-2 text-right">Andil YtD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topInflasiYtD as $index => $item)
                                        <tr class="text-xs text-biru1">
                                            <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                            <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                            <td
                                                class="px-2 py-2 text-right {{ getHeatClass($item->inflasi, $minInflasiYtD, $maxInflasiYtD, $inflasiYtD) }}">
                                                {{ number_format($item->inflasi, 2, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-2 py-2 text-right {{ getHeatClass($item->andil, $minAndilYtD, $maxAndilYtD, $inflasiYtD) }}">
                                                {{ number_format($item->andil, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="h-auto p-4 bg-white border shadow-lg rounded-2xl border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas Penyumbang Inflasi Tahunan<br>
                            <span class="italic font-normal">(Y-on-Y, %)</span>
                        </h2>
                        <div class="shadow-md sm:rounded-lg">
                            <table class="w-full mx-auto text-sm text-left rtl:text-right">
                                <thead class="text-xs text-white bg-biru1">
                                    <tr>
                                        <th scope="col" class="px-2 py-2 text-left"> </th>
                                        <th scope="col" class="px-2 py-2 text-left">Nama Komoditas</th>
                                        <th scope="col" class="px-2 py-2 text-right">Inflasi YoY</th>
                                        <th scope="col" class="px-2 py-2 text-right">Andil YoY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topInflasiYoY as $index => $item)
                                        <tr class="text-xs text-biru1">
                                            <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                            <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                            <td
                                                class="px-2 py-2 text-right {{ getHeatClass($item->inflasi, $minInflasiYoY, $maxInflasiYoY, $inflasiYoY) }}">
                                                {{ number_format($item->inflasi, 2, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-2 py-2 text-right {{ getHeatClass($item->andil, $minAndilYoY, $maxAndilYoY, $inflasiYoY) }}">
                                                {{ number_format($item->andil, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/dashboard/infBulananJatim.js') }}"></script>
    <script>
        const topAndilMtM = @json($topAndilMtM);
        const topAndilYtD = @json($topAndilYtD);
        const topAndilYoY = @json($topAndilYoY);
        const topInflasiMtM = @json($topInflasiMtM);
        const topInflasiYtD = @json($topInflasiYtD);
        const topInflasiYoY = @json($topInflasiYoY);
        window.topAndilMtM = topAndilMtM;
        window.topAndilYtD = topAndilYtD;
        window.topAndilYoY = topAndilYoY;
        window.topInflasiMtM = topInflasiMtM;
        window.topInflasiYtD = topInflasiYtD;
        window.topInflasiYoY = topInflasiYoY;
        console.log("Top Andil MtM:", @json($topAndilMtM));
        console.log("Top Andil YtD:", @json($topAndilYtD));
        console.log("Top Andil YoY:", @json($topAndilYoY));
        document.addEventListener('DOMContentLoaded', function() {
            var wilayahs = @json($wilayahs);

            var map = L.map('map');

            // Add tile layer
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/">CARTO</a>'
            }).addTo(map);

            // Load GeoJSON
            fetch('/data/east-java-districts.geojson')
                .then(response => response.json())
                .then(data => {
                    var filteredFeatures = data.features.filter(feature => {
                        return wilayahs.some(wilayah => wilayah.kode_wil == feature.properties.CC_2);
                    });

                    var filteredGeoJSON = {
                        type: "FeatureCollection",
                        features: filteredFeatures
                    };

                    var geojsonLayer = L.geoJSON(filteredGeoJSON, {
                        style: function(feature) {
                            var wilayah = wilayahs.find(w => w.kode_wil == feature.properties.CC_2);
                            return {
                                fillColor: wilayah ? '#3498db' : '#e0e0e0',
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            layer.bindTooltip(feature.properties.NAME_2, {
                                permanent: true,
                                direction: 'center',
                                className: 'label-tooltip'
                            }).openTooltip();
                        }
                    }).addTo(map);

                    // Auto-fit to the bounds of filtered wilayahs
                    map.fitBounds(geojsonLayer.getBounds().pad(-0.2));
                });
        });
    </script>
@endpush
