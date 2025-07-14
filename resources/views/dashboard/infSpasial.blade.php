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
        <div class="flex flex-col items-center justify-between md:flex-row ">
            <div class="relative flex justify-start mt-7">
                @php
                    $tabs = ['ASEM 1', 'ASEM 2', 'ASEM 3', 'ATAP'];
                @endphp
                @auth
                    @foreach ($tabs as $tab)
                        <a href="{{ route('dashboard.spasial', ['jenis_data_inflasi' => $tab]) }}"
                            class="tab-link flex items-center px-14 py-2 transition-all duration-300 rounded-t-xl {{ $jenisDataInflasi === $tab ? 'bg-biru1 text-white' : 'bg-biru4 text-white' }} hover:bg-biru1 group"
                            data-tab="{{ $tab }}" id="tab-{{ strtolower(str_replace(' ', '-', $tab)) }}">
                            <span class="menu-text text-[15px] font-medium transition duration-100">
                                {{ $tab }}
                            </span>
                        </a>
                    @endforeach
                @endauth
                @guest
                    <a href="{{ route('dashboard.spasial', ['jenis_data_inflasi' => 'ATAP']) }}"
                        class="tab-link flex items-center px-14 py-2 transition-all duration-300 rounded-t-xl bg-biru1 text-white group"
                        data-tab="ATAP" id="tab-atap">
                        <span class="menu-text text-[15px] font-medium transition duration-100">
                            ATAP
                        </span>
                    </a>
                @endguest
            </div>

            <div class="flex items-start gap-2 ">
                <button id="exportExcel"
                    class="flex items-start gap-2 py-2 pl-2 pr-5 transition duration-300 shadow-xl rounded-xl bg-hijau hover:bg-hijau2 hover:-translate-y-1 group">
                    <img src="{{ asset('images/excelIcon.svg') }}" alt="Ikon Eksport Excel" class="w-6 h-6 icon">
                    <span class="menu-text text-white text-[15px] transition duration-100">
                        Export Excel</span>
                </button>
                <button id="exportPdf"
                    class="flex items-end gap-2 py-2 pl-2 pr-5 transition duration-300 shadow-xl rounded-xl bg-merah1 hover:bg-merah1muda hover:-translate-y-1 group">
                    <img src="{{ asset('images/pdfIcon.svg') }}" alt="Ikon Eksport PDF" class="w-6 h-6 icon">
                    <span class="menu-text text-white text-[15px] transition duration-100">
                        Export PDF</span>
                </button>
            </div>
        </div>
    </div>

    <div class="border-t-8 border-biru1">
        <div class="p-6 bg-white rounded-b-xl shadow-md {{ $isBlackWhite ? 'grayscale' : '' }}">
            <!-- === JUDUL === -->
            <div class="w-full max-w-7xl mx-auto flex flex-row gap-6 justify-between pb-8">
                <div class="flex flex-col items-start gap-4 w-full {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                        <h1 class="text-5xl font-bold md:text-5xl text-biru1">Dashboard <span class="text-kuning1">Inflasi
                                Bulanan</span></h1>
                        <h1 class="text-5xl font-bold text-biru1">Provinsi Jawa Timur</h1>
                    </div>
                    <div class="flex flex-row gap-4 pt-1 w-full">
                        <div class="flex flex-row gap-4 text-5xl leading-8 text-biru1">
                            <div class="w-1 h-16 rounded-full bg-biru4"></div>
                            <div>
                                <p class="text-lg text-biru1 opacity-80">Periode Waktu</p>
                                <span class="text-right">{{ $bulan }}</span>
                                <span class="text-right">{{ $tahun }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" flex flex-col items-center justify-end gap-4 pr-6 {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <!-- Filter Periode -->
                    <form method="GET" action="{{ route('dashboard.spasial') }}">
                        <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                        <input type="hidden" name="kabkota" value="{{ $kabkota }}">
                        <input type="hidden" name="komoditas_utama" value="{{ $komoditasUtama }}">
                        {{-- Filter Bulan dan Tahun --}}
                        <div class="flex gap-4 w-80">
                            {{-- Bulan --}}
                            <div class="relative w-1/2">
                                <select id="bulan" name="bulan"
                                    class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    onchange="this.form.submit()">
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($daftarPeriode->pluck('bulan')->unique() as $bulanOption)
                                        <option value="{{ $bulanOption }}"
                                            {{ $bulanOption == $bulan ? 'selected' : '' }}>
                                            {{ $bulanOption }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Tahun --}}
                            <div class="relative w-1/2">
                                <select id="tahun" name="tahun"
                                    class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    onchange="this.form.submit()">
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($daftarPeriode->pluck('tahun')->unique() as $tahunOption)
                                        <option value="{{ $tahunOption }}"
                                            {{ $tahunOption == $tahun ? 'selected' : '' }}>
                                            {{ $tahunOption }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex flex-row gap-5 pb-8">
                {{-- Tabel dan chloroplet --}}
                <div class="w-full mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <div class="flex flex-col gap-6">
                        <!-- Tabel -->
                        <div x-data="{ showModal: false }" class="relative flex flex-col border rounded-xl border-biru1">
                            <!-- Tombol Expand di kanan atas -->
                            <button @click="showModal = true"
                                class="absolute text-2xl font-bold text-white transition duration-200 top-1 right-4 hover:-translate-y-1 focus:outline-none"
                                title="Lihat Ranking Kab/Kota">
                                ⤢
                            </button>
                            <div class="p-2 text-center bg-biru1 rounded-t-xl md:col-span-3">
                                <div class="text-base font-semibold text-white">{{ $jumlahInflasi }} Kabupaten/Kota
                                    Mengalami
                                    Inflasi (M-to-M)
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
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90">
                                <div class="relative w-full max-w-2xl p-6 bg-white shadow-lg rounded-xl">
                                    <!-- Tombol close -->
                                    <button @click="showModal = false"
                                        class="absolute text-2xl font-bold text-gray-500 top-2 right-2 hover:text-red-500 focus:outline-none"
                                        title="Tutup">
                                        &times;
                                    </button>
                                    <h2 class="mb-4 text-lg font-bold text-center text-biru1">Peringkat Kabupaten/Kota
                                        Berdasarkan
                                        Inflasi & Deflasi Umum (M-to-M)</h2>
                                    <div>
                                        <!-- Ranking Inflasi -->
                                        <div class="p-2 bg-white rounded-lg">
                                            <h3 class="mb-2 text-base font-semibold text-merah2">Tabel Peringkat Inflasi
                                            </h3>
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-sm text-left border border-gray-200 rounded-lg">
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
                                                <table class="w-full text-sm text-left border border-gray-200 rounded-lg">
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
                        <div class="relative shadow-md map-container h-96 rounded-2xl">
                            <div id="map" class="z-0 w-full h-full shadow-md rounded-xl"></div>
                            <!-- Legend Choropleth -->
                            <div class="absolute flex flex-col gap-1 px-3 py-2 text-xs bg-white rounded-md shadow left-4 bottom-4 bg-opacity-70"
                                style="z-index: 10;">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block w-4 h-4 rounded-sm" style="background:#E82D1F;"></span>
                                    <span>Inflasi</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block w-4 h-4 rounded-sm" style="background:#388E3C;"></span>
                                    <span>Deflasi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 w-full mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                <div class="relative p-8 overflow-hidden bg-gray-100 shadow-lg rounded-xl">
                    <div class="flex flex-row items-center justify-between w-full mb-6">
                        <div>
                            <h1 class="text-lg font-bold text-biru1 lg:text-xl">Tabel Peringkat Kabupaten/Kota</h1>
                            <h1 class="text-lg font-bold text-biru1 lg:text-xl">Menurut Komoditas Utama</h1>
                            <p class="text-base font-bold text-biru1">{{ $komoditasUtama }}</p>
                        </div>
                        <form method="GET" action="{{ route('dashboard.spasial') }}" class="flex items-end">
                            <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="kabkota" value="{{ $kabkota }}">
                            <div class="w-80">
                                <div class="relative">
                                    <select id="komoditas_utama" name="komoditas_utama"
                                        class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                        onchange="this.form.submit()">
                                        <option value="">Pilih Komoditas</option>
                                        @foreach ($daftarKomoditasUtama as $kom)
                                            <option value="{{ $kom }}"
                                                {{ ($komoditasUtama ?? 'BERAS') == $kom ? 'selected' : '' }}>
                                                {{ $kom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 flex items-center pointer-events-none right-4">
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
                    <div class="flex flex-row gap-6 w-full">
                        <div class="flex-1 min-w-0">
                            <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-xl">
                                <div class="overflow-x-auto">
                                    <table class="w-full ">
                                        <thead class="text-sm border-b-2 bg-biru1 border-gray-50">
                                            <tr>
                                                <th class="px-6 py-2 text-center">
                                                    <div class="flex items-center justify-start gap-1">
                                                        <span
                                                            class="font-semibold tracking-wide text-white">Kabupaten/Kota</span>
                                                    </div>
                                                </th>
                                                <th class="px-3 py-2 text-center">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <span class="font-semibold tracking-wide text-white">Andil MtM
                                                            (%)</span>
                                                    </div>
                                                </th>
                                                <th class="px-3 py-2 text-center">
                                                    <div class="flex items-center justify-center gap-1">
                                                        <span class="font-semibold tracking-wide text-white">Inflasi MtM
                                                            (%)</span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm text-center divide-y divide-gray-100">
                                            @foreach ($rankingKabKota as $index => $item)
                                                <tr class="transition-colors duration-200 hover:bg-gray-50 group">
                                                    <td class="px-4 py-2 text-center">
                                                        <div class="flex items-center justify-start gap-2">
                                                            @if ($index < 3)
                                                                <div
                                                                    class="flex items-center justify-center w-6 h-6 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : ($index === 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-600') }} font-bold ">
                                                                    {{ $index + 1 }}
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="flex items-center justify-center w-6 h-6 font-medium rounded-full bg-blue-50 text-biru1">
                                                                    {{ $index + 1 }}
                                                                </div>
                                                            @endif
                                                            <span
                                                                class="font-normal text-gray-900 transition-colors group-hover:text-biru1 cursor-pointer nama-kabkota"
                                                                data-kode-wil="{{ $item->kode_wil }}"
                                                                data-nama-wil="{{ $item->nama_wil }}">
                                                                {{ $item->nama_wil }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        <div
                                                            class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold {{ getHeatClass($item->andil_mtm, $minAndilKab, $maxAndilKab, $maxAndilKab) }} transition-all duration-200 group-hover:scale-105">
                                                            {{ number_format($item->andil_mtm, 2, ',', '.') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        <div
                                                            class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold {{ getinfClass($item->inflasi_mtm, $maxInflasiKab) }} transition-all duration-200 group-hover:scale-105 text-white">
                                                            {{ number_format($item->inflasi_mtm, 2, ',', '.') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="px-6 py-4 border-t bg-gray-50">
                                    <div class="flex items-center justify-between text-sm text-gray-600">
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                                <span>Inflasi Positif</span>
                                            </div>
                                            <div class="flex items-center gap-2">
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
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div
                                class="h-full bg-white border border-gray-100 shadow-xl rounded-xl flex flex-col justify-between">
                                <div class="flex flex-col h-full">
                                    <div class="flex items-center justify-between px-8 pt-8 pb-2">
                                        <h2 id="judul-barchart-kota-teratas"
                                            class="text-lg font-bold text-biru1 lg:text-xl"></h2>
                                    </div>
                                    <div class="flex-1 flex items-center justify-center px-8 pb-8">
                                        <div class="h-96 w-full" id="barchart-komoditas-kota-teratas"></div>
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
                            <h1 class="text-5xl font-bold md:text-5xl text-biru1">Inflasi Bulanan</h1>
                            <h1 class="text-5xl text-nowrap font-bold text-biru4"> Wilayah
                                {{ ($kabkota ?? '3500') == '3500' ? 'Provinsi Jawa Timur' : $daftarKabKota->firstWhere('kode_wil', $kabkota)->nama_wil ?? 'Provinsi Jawa Timur' }}
                            </h1>
                        </div>

                        {{-- filter --}}
                        <div class="{{ $isBlackWhite ? 'grayscale' : '' }}">
                            <div class="flex justify-end gap-2">
                                <form method="GET" action="{{ route('dashboard.spasial') }}"
                                    class="flex flex-col items-start gap-4">
                                    <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                                    <input type="hidden" name="komoditas_utama" value="{{ $komoditasUtama }}">
                                    <div class="relative w-72">
                                        @php
                                            $user = Auth::user();
                                            $isKabkot = $user && $user->id_role == 2;
                                            $isAsem = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
                                            $shouldRestrict = $isKabkot && $isAsem;
                                        @endphp

                                        @if($shouldRestrict && $daftarKabKota->count() == 1)
                                            {{-- Show as text when only one option --}}
                                            <div class="w-full px-6 py-2 font-semibold text-white rounded-full shadow-md bg-biru4">
                                                {{ $daftarKabKota->first()->nama_wil }}
                                            </div>
                                            <input type="hidden" name="kabkota" value="{{ $daftarKabKota->first()->kode_wil }}">
                                        @else
                                            <select id="kabkota" name="kabkota"
                                                class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                onchange="this.form.submit()">
                                                @if(!$shouldRestrict)
                                                    <option value="">Pilih Kab/Kota</option>
                                                    <option value="3500" {{ ($kabkota ?? '') == '3500' ? 'selected' : '' }}>
                                                        Provinsi Jawa Timur</option>
                                                @endif
                                                @foreach ($daftarKabKota as $kabkotaOption)
                                                    <option value="{{ $kabkotaOption->kode_wil }}"
                                                        {{ ($kabkota ?? '') == $kabkotaOption->kode_wil ? 'selected' : '' }}>
                                                        {{ $kabkotaOption->nama_wil }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-2">
                    <div class="flex items-stretch col-span-3 gap-4">
                        {{-- MtM --}}
                        <div class="flex flex-col flex-1">
                            <div
                                class="flex overflow-hidden relative flex-col p-0 rounded-2xl border border-biru1 shadow-lg">
                                {{-- Bagian atas: label --}}
                                <div class="flex flex-col p-4 items-left bg-biru1">
                                    <div class="text-base font-bold text-white">Inflasi Bulanan (M-to-M, %)</div>
                                    <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan saat
                                        ini terhadap Bulan sebelumnya</div>
                                </div>
                                <div class="border-b border-white opacity-40"></div>
                                {{-- Bagian bawah: badge dan angka utama --}}
                                <div class="relative px-4 pb-4 bg-white rounded-b-2xl">
                                    <div class="absolute top-4 left-4">
                                        @if ($inflasiMtM < 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-hijau bg-green-100 rounded-full">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                Deflasi
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-merah2 bg-red-100 rounded-full">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 15l7-7 7 7" />
                                                </svg>
                                                Inflasi
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-row justify-end">
                                        <span id="inflasiMtM"
                                            class="text-6xl font-bold tracking-tight {{ $inflasiMtM < 0 ? 'text-hijau' : 'text-merah2' }}">
                                            {{ number_format($inflasiMtM, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- YtD --}}
                        <div class="flex flex-col flex-1 gap-2">
                            <div
                                class="flex overflow-hidden relative flex-col p-0 rounded-2xl border border-biru1 shadow-lg">
                                {{-- Bagian atas: label --}}
                                <div class="flex flex-col p-4 items-left bg-biru1">
                                    <div class="text-base font-bold text-white">Inflasi Tahun Kalender (Y-to-D, %)</div>
                                    <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan saat
                                        ini terhadap Bulan Desember Tahun sebelumnya</div>
                                    {{-- <span class="text-xs italic font-normal text-white">(Y-to-D, %)</span> --}}
                                </div>
                                <div class="border-b border-white opacity-40"></div>
                                {{-- Bagian bawah: badge dan angka utama --}}
                                <div class="relative px-4 pb-4 bg-white rounded-b-2xl">
                                    <div class="absolute top-4 left-4">
                                        @if ($inflasiYtD < 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                Deflasi
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 15l7-7 7 7" />
                                                </svg>
                                                Inflasi
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-row justify-end">
                                        <span id="inflasiYtD"
                                            class="text-6xl font-bold tracking-tight {{ $inflasiYtD < 0 ? 'text-hijau' : 'text-merah2' }}">
                                            {{ number_format($inflasiYtD, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- YoY --}}
                        <div class="flex flex-col flex-1 gap-2">
                            <div
                                class="flex overflow-hidden relative flex-col p-0 rounded-2xl border border-biru1 shadow-lg">
                                {{-- Bagian atas: label --}}
                                <div class="flex flex-col p-4 items-left bg-biru1">
                                    <div class="text-base font-bold text-white">Inflasi Tahunan (Y-to-Y, %)</div>
                                    <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan ini di
                                        Tahun saat ini terhadap Bulan ini di Tahun sebelumnya</div>
                                    {{-- <span class="text-xs italic font-normal text-white">(Y-to-Y, %)</span> --}}
                                </div>
                                <div class="border-b border-white opacity-40"></div>
                                {{-- Bagian bawah: badge dan angka utama --}}
                                <div class="relative px-4 pb-4 bg-white rounded-b-2xl">
                                    <div class="absolute top-4 left-4">
                                        @if ($inflasiYoY < 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                Deflasi
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 15l7-7 7 7" />
                                                </svg>
                                                Inflasi
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-row justify-end">
                                        <span id="inflasiYoY"
                                            class="text-6xl font-bold tracking-tight {{ $inflasiYoY < 0 ? 'text-hijau' : 'text-merah2' }}">
                                            {{ number_format($inflasiYoY, 2, ',', '.') }}
                                        </span>
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
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/dashboard/infBulananJatim.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script>
        const topAndilMtM = @json($topAndilMtM);
        const topAndilYtD = @json($topAndilYtD);
        const topAndilYoY = @json($topAndilYoY);
        const topInflasiMtM = @json($topInflasiMtM);
        const topInflasiYtD = @json($topInflasiYtD);
        const topInflasiYoY = @json($topInflasiYoY);
        const inflasiWilayah = @json($inflasiWilayah);
        // Data untuk barchart kabupaten/kota
        const rankingKabKota = @json($rankingKabKota);
        const inflasiKomoditasKotaTeratas = @json($inflasiKomoditasKotaTeratas);
        const daftarKomoditasUtama = @json($daftarKomoditasUtama);
        let currentNamaKota = window.rankingKabKota && window.rankingKabKota.length > 0 ? window.rankingKabKota[0].nama_wil : '';
        let currentKodeKota = window.rankingKabKota && window.rankingKabKota.length > 0 ? window.rankingKabKota[0].kode_wil : '';
        window.topAndilMtM = topAndilMtM;
        window.topAndilYtD = topAndilYtD;
        window.topAndilYoY = topAndilYoY;
        window.topInflasiMtM = topInflasiMtM;
        window.topInflasiYtD = topInflasiYtD;
        window.topInflasiYoY = topInflasiYoY;
        window.rankingKabKota = rankingKabKota;
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

            // MAP GeoJSON
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

        //BARCHART KOMODITAS UTAMA
        async function fetchInflasiKomoditasKabKota(kodeWil) {
            try {
                const params = new URLSearchParams({
                    kode_wil: kodeWil,
                    periode: '{{ $bulan }} {{ $tahun }}',
                    jenis_data_inflasi: '{{ $jenisDataInflasi }}',
                });
                const response = await fetch(`/dashboard/spasial/komoditas-kabkota-data?${params.toString()}`);
                if (!response.ok) throw new Error('Gagal fetch data');
                return await response.json();
            } catch (e) {
                return [];
            }
        }
        function renderBarchartKomoditasKotaTeratas(data, namaKota) {
            var chartDom2 = document.getElementById('barchart-komoditas-kota-teratas');
            var judul = document.getElementById('judul-barchart-kota-teratas');
            if (judul) {
                judul.textContent = 'Inflasi MtM Komoditas Utama di ' + (namaKota || '-');
            }
            if (chartDom2 && data && data.length > 0) {
                var myChart2 = echarts.init(chartDom2);
                var labels2 = data.map(item => item.nama_kom);
                var values2 = data.map(item => Number(item.inflasi_mtm));
                var option2 = {
                    tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
                    grid: { left: '5%', right: '5%', bottom: '3%', top: 10, containLabel: true },
                    xAxis: { type: 'value', boundaryGap: [0, 0.01] },
                    yAxis: {
                        type: 'category',
                        data: labels2,
                        inverse: true,
                        axisLabel: { color: '#000000', fontSize: 12, fontWeight: 330 },
                    },
                    series: [{
                        type: 'bar',
                        data: values2,
                        itemStyle: { color: '#E82D1F' },
                        label: {
                            show: true,
                            position: 'outside',
                            color: '#063051',
                            fontSize: 12,
                            fontWeight: 350,
                            formatter: function(params) { return params.value.toFixed(2).replace('.', ','); },
                        },
                    }],
                };
                myChart2.setOption(option2);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                renderBarchartKomoditasKotaTeratas(inflasiKomoditasKotaTeratas, currentNamaKota);
                document.querySelectorAll('.nama-kabkota').forEach(function(el) {
                    el.addEventListener('click', async function() {
                        const kodeWil = this.getAttribute('data-kode-wil');
                        const namaWil = this.getAttribute('data-nama-wil');
                        // Fetch data via AJAX
                        const data = await fetchInflasiKomoditasKabKota(kodeWil);
                        renderBarchartKomoditasKotaTeratas(data, namaWil);
                    });
                });
            }, 500);
        });
    </script>
@endpush
