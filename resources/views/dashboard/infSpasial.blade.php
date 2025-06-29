@extends('layouts.dashboard')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            width: 100%;
            height: 100%;
            /* Kotak */
            max-width: 100%;
        }

        .summary-box {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            gap: 10px;
        }

        .summary-box>div {
            display: flex;
            justify-content: space-between;
            background: #f4f4f4;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
        }

        .label-tooltip {
            background-color: rgba(255, 255, 255, 0.3);
            border: 1px solid #ddd;
            padding: 5px 10px;
            font-size: 14px;
            color: #063051;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .label-positif {
            color: #E82D1F;
            font-weight: bold;
        }

        .label-negatif {
            color: #388E3C;
            font-weight: bold;
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

    function getinfClass($value, $top)
    {
        $isPositive = $top > 0;

        if ($isPositive) {
            return 'bg-hijau';
        } else {
            return 'bg-merah1';
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
    $minAndilKab = $rankingKabKota->min('andil_mtm');
    $maxAndilKab = $rankingKabKota->max('andil_mtm');
    $minInflasiKab = $rankingKabKota->min('inflasi_mtm');
    $maxInflasiKab = $rankingKabKota->max('inflasi_mtm');

    // Add condition for black and white mode
    $isBlackWhite = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
@endphp

@section('body')
    <div class="container mx-auto">
        <div class="flex flex-col justify-between items-center md:flex-row">
            <div class="flex relative justify-start mt-7">
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

            <div class="flex gap-2 items-start">
                <button id="exportExcel"
                    class="flex gap-2 items-start py-2 pr-5 pl-2 rounded-xl shadow-xl transition duration-300 bg-hijau hover:bg-hijau2 hover:-translate-y-1 group">
                    <img src="{{ asset('images/excelIcon.svg') }}" alt="Ikon Eksport Excel" class="w-6 h-6 icon">
                    <span class="menu-text text-white text-[15px] transition duration-100">
                        Export Excel</span>
                </button>
                <button id="exportPdf"
                    class="flex gap-2 items-end py-2 pr-5 pl-2 rounded-xl shadow-xl transition duration-300 bg-merah1 hover:bg-merah1muda hover:-translate-y-1 group">
                    <img src="{{ asset('images/pdfIcon.svg') }}" alt="Ikon Eksport PDF" class="w-6 h-6 icon">
                    <span class="menu-text text-white text-[15px] transition duration-100">
                        Export PDF</span>
                </button>
            </div>
        </div>
    </div>

    <div class="border-t-8 border-biru1">
        <div class="p-6 bg-white rounded-b-xl shadow-md {{ $isBlackWhite ? 'grayscale' : '' }}">
            <div class="flex flex-row gap-6">
                <!-- Judul -->
                <div class="grid items-start pb-6 pl-6 grid-row-1 gap-4 md:grid-rows-2 {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                        <h1 class="text-5xl font-bold md:text-5xl text-biru1">Dashboard <span class="text-kuning1">Inflasi
                                Bulanan</span></h1>
                        <h1 class="text-5xl font-bold text-biru1">Provinsi Jawa Timur</h1>
                    </div>
                    <div class="grid grid-cols-1 items-start pt-1 md:grid-cols-2">
                        {{-- judul --}}
                        <div class=" {{ $isBlackWhite ? 'grayscale' : '' }}">
                            <div class="flex flex-row gap-4 pr-36 text-5xl leading-8 text-biru1">
                                <div class="w-1 h-16 rounded-full opacity-80 bg-biru4"></div>
                                <div>
                                    <p class="text-lg opacity-80 text-biru1">Periode Waktu</p>
                                    <span class="text-right">{{ $bulan }}</span>
                                    <span class="text-right">{{ $tahun }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-end justify-end gap-4 pr-8 pl-18 {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <!-- Filter Periode -->
                    <div >
                        <form method="GET" action="{{ route('dashboard.spasial') }}"
                            class="flex flex-col gap-4 items-start pr-8 pl-18">
                            <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                            <input type="hidden" name="kabkota" value="{{ $kabkota }}">
                            <input type="hidden" name="komoditas_utama" value="{{ $komoditasUtama }}">
                            {{-- Filter Bulan dan Tahun --}}
                            <div class="grid grid-cols-1 gap-4 mx-auto w-full max-w-7xl md:grid-cols-2">
                                {{-- Bulan --}}
                                <div class="relative">
                                    <select id="bulan" name="bulan"
                                        class="px-6 py-2 pr-12 w-full font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                        onchange="this.form.submit()">
                                        <option value="">Pilih Bulan</option>
                                        @foreach ($daftarPeriode->pluck('bulan')->unique() as $bulanOption)
                                            <option value="{{ $bulanOption }}"
                                                {{ $bulanOption == $bulan ? 'selected' : '' }}>
                                                {{ $bulanOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="flex absolute inset-y-0 right-3 items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                {{-- Tahun --}}
                                <div class="relative">
                                    <select id="tahun" name="tahun"
                                        class="px-6 py-2 pr-1 w-full font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                        onchange="this.form.submit()">
                                        <option value="">Pilih Tahun</option>
                                        @foreach ($daftarPeriode->pluck('tahun')->unique() as $tahunOption)
                                            <option value="{{ $tahunOption }}"
                                                {{ $tahunOption == $tahun ? 'selected' : '' }}>
                                                {{ $tahunOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="flex absolute inset-y-0 right-3 items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                {{-- Tombol Submit --}}
                            </div>
                        </form>
                    </div>

                    <!-- Filter Komoditas -->
                    <div class="flex flex-col justify-end items-end pb-4">
                        <form method="GET" action="{{ route('dashboard.spasial') }}"
                            class="flex flex-col gap-4 items-end">
                            <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="kabkota" value="{{ $kabkota }}">

                            <div class="w-full max-w-sm">
                                <div class="relative">
                                    <select id="komoditas_utama" name="komoditas_utama"
                                        class="px-6 py-2 pr-10 w-80 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                        onchange="this.form.submit()">
                                        <option value="">Pilih Komoditas</option>
                                        @foreach ($daftarKomoditasUtama as $kom)
                                            <option value="{{ $kom }}"
                                                {{ ($komoditasUtama ?? 'BERAS') == $kom ? 'selected' : '' }}>
                                                {{ $kom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="flex absolute inset-y-0 right-4 items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-white transition-transform duration-200" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="flex flex-row gap-5">
                {{-- Tabel dan chloroplet --}}
                <div class="w-full mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <div class="flex flex-col gap-6 max-w-7xl">
                        <!-- Tabel -->
                        <div x-data="{ showModal: false }" class="flex relative flex-col rounded-xl border border-biru1">
                            <!-- Tombol Expand di kanan atas -->
                            <button @click="showModal = true"
                                class="absolute top-1 right-4 text-2xl font-bold text-white transition duration-200 hover:-translate-y-1 focus:outline-none"
                                title="Lihat Ranking Kab/Kota">
                                ⤢
                            </button>
                            <div class="p-2 text-center rounded-t-xl bg-biru1 md:col-span-3">
                                <div class="text-base font-semibold text-white">{{ $jumlahInflasi }} Kabupaten/Kota
                                    Mengalami Inflasi Umum (M-to-M)
                                </div>
                            </div>
                            <div class="grid grid-cols-1 bg-white divide-x divide-gray-300 md:grid-cols-2">
                                <div class="p-1 text-center">
                                    <div class="text-xl font-semibold text-merah2">Top Kota Inflasi</div>
                                    <div class="text-2xl font-bold text-merah2">
                                        {{ $rankingInflasi->first()->nama_wil ?? '-' }}</div>
                                    <div class="text-base text-biru1">
                                        ({{ number_format($rankingInflasi->first()->inflasi_mtm ?? 0, 2) }} %) </div>
                                </div>
                                <div class="p-1 text-center">
                                    <div class="text-xl font-semibold text-hijau">Top Kota Deflasi</div>
                                    <div class="text-2xl font-bold text-hijau">
                                        {{ $rankingDeflasi->first()->nama_wil ?? '-' }}</div>
                                    <div class="text-base text-biru1">
                                        ({{ number_format($rankingDeflasi->first()->inflasi_mtm ?? 0, 2) }} %) </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div x-show="showModal" x-cloak
                                class="flex fixed inset-0 z-50 justify-center items-center bg-black bg-opacity-40"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90">
                                <div class="relative p-6 w-full max-w-2xl bg-white rounded-xl shadow-lg">
                                    <!-- Tombol close -->
                                    <button @click="showModal = false"
                                        class="absolute top-2 right-2 text-2xl font-bold text-gray-500 hover:text-red-500 focus:outline-none"
                                        title="Tutup">
                                        &times;
                                    </button>
                                    <h2 class="mb-4 text-lg font-bold text-center text-biru1">Peringkat Kabupaten/Kota
                                        Berdasarkan Inflasi & Deflasi Umum (M-to-M)</h2>
                                    <div>
                                        <!-- Ranking Inflasi -->
                                        <div class="p-2 bg-white rounded-lg">
                                            <h3 class="mb-2 text-base font-semibold text-merah2">Tabel Peringkat Inflasi
                                            </h3>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-sm text-left rounded-lg border border-gray-200">
                                                    <thead class="text-xs text-white bg-biru1">
                                                        <tr>
                                                            <th class="px-2 py-2">No</th>
                                                            <th class="px-2 py-2">Kabupaten/Kota</th>
                                                            <th class="px-2 py-2 text-right">Inflasi MtM (%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($rankingInflasi as $index => $item)
                                                            <tr class="text-biru1">
                                                                <td class="px-2 py-2">{{ $index + 1 }}</td>
                                                                <td class="px-2 py-2">{{ $item->nama_wil }}</td>
                                                                <td class="px-2 py-2 text-right text-merah2">
                                                                    {{ number_format($item->inflasi_mtm, 2) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3"
                                                                    class="px-2 py-4 text-center text-gray-400">Tidak ada
                                                                    kabupaten/kota yang mengalami inflasi</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Ranking Deflasi -->
                                        <div class="p-2 bg-white rounded-lg">
                                            <h3 class="mb-2 text-base font-semibold text-hijau">Tabel Peringkat Deflasi
                                            </h3>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-sm text-left rounded-lg border border-gray-200">
                                                    <thead class="text-xs text-white bg-biru1">
                                                        <tr>
                                                            <th class="px-2 py-2">No</th>
                                                            <th class="px-2 py-2">Kabupaten/Kota</th>
                                                            <th class="px-2 py-2 text-right">Deflasi MtM (%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($rankingDeflasi as $index => $item)
                                                            <tr class="text-biru1">
                                                                <td class="px-2 py-2">{{ $index + 1 }}</td>
                                                                <td class="px-2 py-2">{{ $item->nama_wil }}</td>
                                                                <td class="px-2 py-2 text-right text-hijau">
                                                                    {{ number_format($item->inflasi_mtm, 2) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3"
                                                                    class="px-2 py-4 text-center text-gray-400">Tidak ada
                                                                    kabupaten/kota yang mengalami deflasi</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chloroplet -->
                        <div>
                            <div class="relative h-96 rounded-2xl shadow-md map-container">
                                <div id="map" class="z-0 w-full h-full rounded-xl shadow-md"></div>
                                <!-- Legend Choropleth -->
                                <div class="flex absolute bottom-4 left-4 flex-col gap-1 px-3 py-2 text-xs bg-white bg-opacity-70 rounded-md shadow"
                                    style="z-index: 10;">
                                    <div class="flex gap-2 items-center">
                                        <span class="inline-block w-4 h-4 rounded-sm" style="background:#E82D1F;"></span>
                                        <span>Inflasi</span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="inline-block w-4 h-4 rounded-sm" style="background:#388E3C;"></span>
                                        <span>Deflasi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Komoditas - Improved Version --}}
                <div class="w-full mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <div class="overflow-hidden relative p-8 mb-8 bg-gray-100 rounded-xl shadow-lg">
                        <!-- Header Section dengan Background -->
                        <div class="flex relative flex-col gap-8">
                            <div>
                                <!-- Title Section -->
                                <div>
                                    <h1 class="text-base font-bold text-biru1">Tabel Peringkat Kabupaten/Kota Menurut
                                        Komoditas Utama</h1>
                                    <div class="flex items-center mt-2 font-bold text-biru1">
                                        <div>
                                            <p class="text-sm font-semibold">{{ $komoditasUtama }} -
                                                {{ $bulan }}
                                                {{ $tahun }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Table Section --}}
                            <div class="overflow-hidden col-span-2 bg-white rounded-xl border border-gray-100 shadow-xl">
                                <!-- Table Content -->
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="text-sm border-b-2 border-gray-50 bg-biru1">
                                            <tr>
                                                <th class="px-6 py-2 text-center" colspan="1">
                                                    <div class="flex gap-1 justify-start items-center">
                                                        <span
                                                            class="font-semibold tracking-wide text-white">Kabupaten/Kota</span>
                                                    </div>
                                                </th>
                                                <th class="px-3 py-2 text-center">
                                                    <div class="flex gap-1 justify-center items-center">
                                                        <span class="font-semibold tracking-wide text-white">Andil MtM
                                                            (%)</span>
                                                    </div>
                                                </th>
                                                <th class="px-3 py-2 text-center">
                                                    <div class="flex gap-1 justify-center items-center">
                                                        <span class="font-semibold tracking-wide text-white">Inflasi MtM
                                                            (%)</span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm text-center divide-y divide-gray-100">
                                            @foreach ($rankingKabKota as $index => $item)
                                                <tr class="transition-colors duration-200 hover:bg-abubiru">
                                                    <!-- Gabungan Ranking + Nama Wilayah -->
                                                    <td class="px-4 py-2 text-center">
                                                        <div class="flex gap-2 justify-start items-center">
                                                            @if ($index < 3)
                                                                <div
                                                                    class="flex items-center justify-center w-6 h-6 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : ($index === 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-600') }} font-bold ">
                                                                    {{ $index + 1 }}
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="flex justify-center items-center w-6 h-6 font-medium bg-blue-50 rounded-full text-biru1">
                                                                    {{ $index + 1 }}
                                                                </div>
                                                            @endif
                                                            <span
                                                                class="font-normal text-gray-900 transition-colors group-hover:text-biru1">
                                                                {{ $item->nama_wil }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <!-- Andil MtM -->
                                                    <td class="px-3 py-2 text-center">
                                                        <div
                                                            class="inline-flex items-center px-2 py-0.5 font-medium rounded-full">
                                                            {{ number_format($item->andil_mtm, 2, ',', '.') }}
                                                        </div>
                                                    </td>
                                                    <!-- Inflasi MtM -->
                                                    <td class="px-3 py-2 text-center">
                                                        <div
                                                            class="inline-flex items-center px-2 py-0.5 rounded-full font-medium text-white {{ getinfClass($item->inflasi_mtm, $maxInflasiKab) }}">
                                                            {{ number_format($item->inflasi_mtm, 2, ',', '.') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Table Footer -->
                                <div class="px-6 py-4 bg-gray-50 border-t">
                                    <div class="flex justify-between items-center text-sm text-gray-600">
                                        <div class="flex gap-4 items-center">
                                            <div class="flex gap-2 items-center">
                                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                                <span>Inflasi Positif</span>
                                            </div>
                                            <div class="flex gap-2 items-center">
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                                <span>Deflasi</span>
                                            </div>
                                        </div>
                                        <div class="font-medium text-biru1">
                                            Total: {{ count($rankingKabKota) }} Kabupaten/Kota
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- ini buat dash spasialnya --}}
            <div>
                <div class="w-full pt-12 pb-6 mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <div
                        class="grid items-start grid-cols-1 gap-10 md:grid-cols-2 {{ $isBlackWhite ? 'grayscale' : '' }}">
                        {{-- judul --}}
                        <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                            <h1 class="text-5xl font-bold md:text-4xl text-biru4">INFLASI BULANAN WILAYAH</h1>
                            <h1 class="text-5xl font-bold text-biru1">
                                {{ ($kabkota ?? '3500') == '3500' ? 'Provinsi Jawa Timur' : $daftarKabKota->firstWhere('kode_wil', $kabkota)->nama_wil ?? 'Provinsi Jawa Timur' }}
                            </h1>
                        </div>

                        {{-- filter --}}
                        <div class="{{ $isBlackWhite ? 'grayscale' : '' }}">
                            <div class="flex gap-2 justify-end">
                                <form method="GET" action="{{ route('dashboard.spasial') }}"
                                    class="flex flex-col gap-4 items-start">
                                    <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                                    <input type="hidden" name="komoditas_utama" value="{{ $komoditasUtama }}">
                                    <div class="relative w-72">
                                        <select id="kabkota" name="kabkota"
                                            class="px-6 py-2 pr-10 w-full font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                            onchange="this.form.submit()">
                                            <option value="">Pilih Kab/Kota</option>
                                            <option value="3500" {{ ($kabkota ?? '') == '3500' ? 'selected' : '' }}>
                                                Provinsi Jawa Timur</option>
                                            @foreach ($daftarKabKota as $kabkotaOption)
                                                <option value="{{ $kabkotaOption->kode_wil }}"
                                                    {{ ($kabkota ?? '') == $kabkotaOption->kode_wil ? 'selected' : '' }}>
                                                    {{ $kabkotaOption->nama_wil }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="flex absolute inset-y-0 right-3 items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-2">
                    <div class="flex col-span-3 gap-4 items-stretch">
                        {{-- MtM --}}
                        <div class="flex flex-col flex-1 gap-2">
                            <div class="text-xs italic leading-tight text-white">
                                <span class="text-biru4">Nilai inflasi pada Bulan saat ini terhadap Bulan sebelumnya</span>
                            </div>
                            <div
                                class="flex flex-col flex-1 justify-between px-4 py-2 h-full text-white rounded-2xl shadow-lg bg-biru1">
                                <div class="flex justify-between items-end">
                                    <div class="text-sm font-bold">
                                        Nilai Inflasi Bulanan<br>
                                        <span class="text-xs italic font-normal">(M-to-M, %)</span>
                                    </div>
                                    <div id="inflasiMtM"
                                        class="text-5xl font-semibold {{ $inflasiMtM < 0 ? 'text-hijau' : 'text-merah2' }} {{ $isBlackWhite ? 'text-white' : '' }}">
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
                                class="flex flex-col flex-1 justify-between px-4 py-2 h-full text-white rounded-2xl shadow-lg bg-biru1">
                                <div class="flex justify-between items-end">
                                    <div class="text-sm font-bold">
                                        Nilai Inflasi Tahun Kalender<br>
                                        <span class="text-xs italic font-normal">(Y-to-D, %)</span>
                                    </div>
                                    <div id="inflasiYtD"
                                        class="text-5xl font-semibold {{ $inflasiYtD < 0 ? 'text-hijau' : 'text-merah2' }} {{ $isBlackWhite ? 'text-white' : '' }}">
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
                                class="flex flex-col flex-1 justify-between px-4 py-2 h-full text-white rounded-2xl shadow-lg bg-biru1">
                                <div class="flex justify-between items-end">
                                    <div class="text-sm font-bold">
                                        Nilai Inflasi Tahunan<br>
                                        <span class="text-xs italic font-normal">(Y-to-Y, %)</span>
                                    </div>
                                    <div id="inflasiYoY"
                                        class="text-5xl font-semibold {{ $inflasiYoY < 0 ? 'text-hijau' : 'text-merah2' }} {{ $isBlackWhite ? 'text-white' : '' }}">
                                        {{ number_format($inflasiYoY, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- js echart barchart --}}
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <div class="p-3 bg-white rounded-2xl border shadow-lg border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas dengan <br> Sumbangan Inflasi Bulanan Terbesar<br>
                            <span class="italic font-normal">(M-to-M, %)</span>
                        </h2>
                        <div class="h-80" id="andilmtm"></div>
                    </div>

                    <div class="p-3 bg-white rounded-2xl border shadow-lg border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas dengan <br> Sumbangan Inflasi Tahun Kalender Terbesar<br>
                            <span class="italic font-normal">(Y-to-D, %)</span>
                        </h2>
                        <div class="h-80" id="andilytd"></div>
                    </div>

                    <div class="p-3 bg-white rounded-2xl border shadow-lg border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas dengan <br> Sumbangan Inflasi Tahunan Terbesar<br>
                            <span class="italic font-normal">(Y-on-Y, %)</span>
                        </h2>
                        <div class="h-80" id="andilyoy"></div>
                    </div>
                </div>

                {{-- tabel top komoditas --}}
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <div class="p-4 h-auto bg-white rounded-2xl border shadow-lg border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas Penyumbang Inflasi Bulanan<br>
                            <span class="italic font-normal">(M-to-M, %)</span>
                        </h2>
                        <div class="shadow-md sm:rounded-lg">
                            <table class="mx-auto w-full text-sm text-left rtl:text-right">
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

                    <div class="p-4 h-auto bg-white rounded-2xl border shadow-lg border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas Penyumbang Inflasi Tahun Kalender<br>
                            <span class="italic font-normal">(Y-to-D, %)</span>
                        </h2>
                        <div class="shadow-md sm:rounded-lg">
                            <table class="mx-auto w-full text-sm text-left rtl:text-right">
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

                    <div class="p-4 h-auto bg-white rounded-2xl border shadow-lg border-biru1">
                        <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                            10 Komoditas Penyumbang Inflasi Tahunan<br>
                            <span class="italic font-normal">(Y-on-Y, %)</span>
                        </h2>
                        <div class="shadow-md sm:rounded-lg">
                            <table class="mx-auto w-full text-sm text-left rtl:text-right">
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
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/dashboard/infBulananJatim.js') }}"></script>
    <script>
        const topAndilMtM = @json($topAndilMtM);
        const topAndilYtD = @json($topAndilYtD);
        const topAndilYoY = @json($topAndilYoY);
        const topInflasiMtM = @json($topInflasiMtM);
        const topInflasiYtD = @json($topInflasiYtD);
        const topInflasiYoY = @json($topInflasiYoY);
        const inflasiWilayah = @json($inflasiWilayah);
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
                            var wilayah = inflasiWilayah.find(w => w.kode_wil == feature.properties
                                .CC_2);
                            // Warna: merah jika inflasi >= 0, hijau jika < 0, abu jika tidak ada data
                            let fillColor = '#e0e0e0';
                            if (wilayah) {
                                fillColor = wilayah.inflasi_mtm < 0 ? '#27ae60' : '#e74c3c';
                            }
                            return {
                                fillColor: fillColor,
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            var wilayah = inflasiWilayah.find(w => w.kode_wil == feature.properties
                                .CC_2);
                            let label = feature.properties.NAME_2;
                            let inflasi = null;
                            let inflasiStr = '';
                            if (wilayah) {
                                inflasi = parseFloat(wilayah.inflasi_mtm);
                                inflasiStr = `(${inflasi.toFixed(2).replace('.', ',')} %)`;
                                inflasiColor = inflasi < 0 ? '#388E3C' : '#E82D1F';
                                label =
                                    `<div style='text-align:center;'>` +
                                    `<div style='font-weight:bold;text-transform:uppercase;'>${wilayah.nama_wil}</div>` +
                                    `<div style='color:${inflasiColor};font-weight:bold;'>${inflasiStr}</div>` +
                                    `</div>`;
                            } else {
                                label =
                                    `<div style='text-align:center;'>` +
                                    `<div style='font-weight:bold;text-transform:uppercase;'>${feature.properties.NAME_2}</div>` +
                                    `<div style='color:#888;'>-</div>` +
                                    `</div>`;
                            }
                            layer.bindTooltip(label, {
                                permanent: true,
                                direction: 'top',
                                className: 'label-tooltip'
                            }).openTooltip();
                        }
                    }).addTo(map);

                    if (filteredGeoJSON.features.length > 0) {
                        map.fitBounds(geojsonLayer.getBounds().pad(-0.25));
                        setTimeout(function() {
                            map.panBy([-90, 150]);
                        }, 150);
                    } else {
                        map.setView([-7.6, 112.0], 8);
                    }
                })
                .catch(error => {
                    console.error('Gagal memuat atau memproses GeoJSON:', error);
                    map.setView([-7.6, 112.0], 8);
                });
        });
    </script>
@endpush
