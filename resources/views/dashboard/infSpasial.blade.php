@extends('layouts.dashboard')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/dashboard/infspasial.css') }}" />
@endpush

@section('body')
    <div class="flex items-start gap-2 ">
        <x-dash-header :tabs="['ASEM 1', 'ASEM 2', 'ASEM 3', 'ATAP']" :activeTab="$jenisDataInflasi" routeName="dashboard.spasial" :routeParams="['bulan' => $bulan, 'tahun' => $tahun, 'kabkota' => $kabkota, 'komoditas_utama' => $komoditasUtama]" :showDropdown="false"
            :showExcel="true" :showPng="true" exportExcelId="exportExcel" exportPngId="exportPNG" class="mb-4" />
    </div>

    <div class="border-t-8 border-biru1">
        <div id="main-dashboard-content" class="p-6 bg-white rounded-b-xl shadow-md {{ $isBlackWhite ? 'grayscale' : '' }}">

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
                                    @foreach ($daftarPeriode->where('tahun', $tahun)->pluck('bulan')->unique() as $bulanOption)
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
                                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
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
            @if ($isAdminProv && $isAsem)
                <div class="px-6 w-full mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }} py-10">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="flex justify-end mb-4">
                            <button id="exportExcelTabelDinamis"
                                class="flex items-center gap-2 px-4 py-2 rounded-xl shadow-xl bg-hijau hover:bg-hijau2 text-white text-sm font-medium">
                                <img src="{{ asset('images/excelIcon.svg') }}" alt="Excel" class="h-5 w-5">Export
                                Excel Tabel Dinamis
                            </button>
                        </div>
                        <h2 class="text-2xl font-bold text-biru1 mb-6">Tabel Dinamis</h2>
                        <form id="form-tabel-dinamis">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                                <!-- Komoditas -->
                                <div>
                                    <label class="block text-biru1 font-semibold mb-2">Cari Komoditas</label>
                                    <input type="text" id="search-komoditas" placeholder="Cari komoditas..."
                                        class="w-full px-4 py-2 border rounded-lg mb-2 focus:ring focus:ring-blue-200">
                                    <div id="checklist-komoditas"
                                        class="border rounded-lg p-2 h-40 overflow-y-auto bg-gray-50">
                                        @foreach ($daftarSemuaKomoditas as $kom)
                                            <label class="block"><input type="checkbox" name="komoditas[]"
                                                    value="{{ $kom }}"> {{ $kom }}</label>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Wilayah/Satker -->
                                <div>
                                    <label class="block text-biru1 font-semibold mb-2">Cari Wilayah/Satker</label>
                                    <input type="text" id="search-wilayah" placeholder="Cari wilayah/satker..."
                                        class="w-full px-4 py-2 border rounded-lg mb-2 focus:ring focus:ring-blue-200">
                                    <div id="checklist-wilayah"
                                        class="border rounded-lg p-2 h-40 overflow-y-auto bg-gray-50">
                                        <label class="block font-medium text-biru1"><input type="checkbox"
                                                id="check-wilayah-semua"> Pilih Semua</label>
                                        @foreach ($daftarKabKota as $wil)
                                            <label class="block"><input type="checkbox" name="wilayah[]"
                                                    value="{{ $wil->kode_wil }}"> {{ $wil->nama_wil }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                                <!-- Jenis Data (Value) -->
                                <div>
                                    <label class="block text-biru1 font-semibold mb-2">Jenis Data</label>
                                    <div id="checklist-value"
                                        class="border rounded-lg p-2 bg-gray-50 flex flex-col gap-2">
                                        <label><input type="radio" name="value" value="inf_mtm"> Inflasi MtM</label>
                                        <label><input type="radio" name="value" value="inf_ytd"> Inflasi YtD</label>
                                        <label><input type="radio" name="value" value="inf_yoy"> Inflasi YoY</label>
                                        <label><input type="radio" name="value" value="andil_mtm"> Andil MtM</label>
                                        <label><input type="radio" name="value" value="andil_ytd"> Andil YtD</label>
                                        <label><input type="radio" name="value" value="andil_yoy"> Andil YoY</label>
                                    </div>
                                </div>
                                <!-- Periode -->
                                <div>
                                    <label class="block text-biru1 font-semibold mb-2">Periode</label>
                                    <div id="checklist-periode"
                                        class="border rounded-lg p-2 h-40 overflow-y-auto bg-gray-50 flex flex-col gap-2">
                                        @foreach ($daftarPeriode as $periode)
                                            <label class="block"><input type="radio" name="periode"
                                                    value="{{ $periode['bulan'] }} {{ $periode['tahun'] }}">
                                                {{ $periode['bulan'] }} {{ $periode['tahun'] }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center mt-6">
                                <button type="button" id="btn-tampilkan-tabel"
                                    class="px-8 py-2 bg-biru1 text-white rounded-lg font-semibold shadow hover:bg-biru4 transition">Tampilkan</button>
                            </div>
                        </form>
                        <div id="tabel-dinamis-hasil" class="mt-10"></div>
                    </div>
                </div>
            @else
                <div class=" w-full mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <div class="relative p-8 overflow-hidden bg-gray-100 shadow-lg rounded-xl">
                        <div class="flex flex-row items-center justify-between w-full mb-6">
                            <div>
                                <h1 class="text-3xl font-bold text-biru1 lg:text-3xl">Tabel Peringkat Kabupaten/Kota</h1>
                                <h1 class="text-3xl font-bold text-biru1 lg:text-3xl">Menurut Komoditas Utama</h1>
                                <h1 class="text-3xl font-bold text-biru4">{{ $komoditasUtama }}</h1>
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
                                                class="w-5 h-5 text-white transition-transform duration-200"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="flex flex-row w-full">
                            <div class="flex-1 min-w-0 flex justify-end">
                                <div class="w-3/4">
                                    <div class="overflow-hidden bg-white border border-gray-100 shadow-xl rounded-xl">
                                        <div class="overflow-x-auto">
                                            <table class="w-full ">
                                                <thead class="text-sm  bg-biru1">
                                                    <tr>
                                                        <th class="px-6 py-2 text-left">
                                                            <span
                                                                class="font-semibold tracking-wide text-white">Kabupaten/Kota</span>
                                                        </th>
                                                        <th class="px-4 py-2 text-right">
                                                            <span class="font-semibold tracking-wide text-white">Andil MtM
                                                                (%)</span>
                                                        </th>
                                                        <th class="px-4 py-2 text-right">
                                                            <span class="font-semibold tracking-wide text-white">Inflasi
                                                                MtM (%)</span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-sm">
                                                    @foreach ($rankingKabKota as $index => $item)
                                                        <tr class="transition-colors duration-200 hover:bg-gray-50 group">
                                                            <td class="px-4 py-2 text-left align-middle">
                                                                <span
                                                                    class="font-normal text-gray-900 transition-colors group-hover:text-biru1 cursor-pointer nama-kabkota"
                                                                    data-kode-wil="{{ $item->kode_wil }}"
                                                                    data-nama-wil="{{ $item->nama_wil }}">
                                                                    {{ $item->nama_wil }}
                                                                </span>
                                                            </td>
                                                            <td
                                                                class="px-4 py-2 text-right align-middle {{ \App\Helpers\InfSpasialHelper::getHeatClass($item->andil_mtm, $minAndilKab, $maxAndilKab, $maxAndilKab) }}">
                                                                <span class="angka-screenshot font-semibold">
                                                                    {{ number_format($item->andil_mtm, 2, ',', '.') }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-2 text-right align-middle">
                                                                <span
                                                                    class="angka-screenshot font-semibold {{ $item->inflasi_mtm < 0 ? 'text-hijau' : 'text-merah2' }}">
                                                                    {{ number_format($item->inflasi_mtm, 2, ',', '.') }}
                                                                </span>
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
                                            @endphp

                                            @if ($isKabkot && $daftarKabKota->count() == 1)
                                                {{-- Show as text when only one option --}}
                                                <div
                                                    class="w-full px-6 py-2 font-semibold text-white rounded-full shadow-md bg-biru4">
                                                    {{ $daftarKabKota->first()->nama_wil }}
                                                </div>
                                                <input type="hidden" name="kabkota"
                                                    value="{{ $daftarKabKota->first()->kode_wil }}">
                                            @else
                                                <select id="kabkota" name="kabkota"
                                                    class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                    onchange="this.form.submit()">
                                                    @if (!$isKabkot)
                                                        <option value="">Pilih Kab/Kota</option>
                                                        <option value="3500"
                                                            {{ ($kabkota ?? '') == '3500' ? 'selected' : '' }}>
                                                            Provinsi Jawa Timur</option>
                                                    @endif
                                                    @foreach ($daftarKabKota as $kabkotaOption)
                                                        <option value="{{ $kabkotaOption->kode_wil }}"
                                                            {{ ($kabkota ?? '') == $kabkotaOption->kode_wil ? 'selected' : '' }}>
                                                            {{ $kabkotaOption->nama_wil }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 9l-7 7-7-7" />
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
                                        <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan
                                            saat
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
                                        {{-- === SUMMARY ANGKA UTAMA === --}}
                                        <div class="flex flex-row justify-end">
                                            <span id="inflasiMtM"
                                                class="angka-screenshot text-6xl font-bold tracking-tight {{ $inflasiMtM < 0 ? 'text-hijau' : 'text-merah2' }}">
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
                                        <div class="text-base font-bold text-white">Inflasi Tahun Kalender (Y-to-D, %)
                                        </div>
                                        <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan
                                            saat
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
                                        {{-- === SUMMARY ANGKA UTAMA === --}}
                                        <div class="flex flex-row justify-end">
                                            <span id="inflasiYtD"
                                                class="angka-screenshot text-6xl font-bold tracking-tight {{ $inflasiYtD < 0 ? 'text-hijau' : 'text-merah2' }}">
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
                                        <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan
                                            ini di
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
                                        {{-- === SUMMARY ANGKA UTAMA === --}}
                                        <div class="flex flex-row justify-end">
                                            <span id="inflasiYoY"
                                                class="angka-screenshot text-6xl font-bold tracking-tight {{ $inflasiYoY < 0 ? 'text-hijau' : 'text-merah2' }}">
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
                                            <th scope="col" class="px-2 py-2 text-right">Andil MtM</th>
                                            <th scope="col" class="px-2 py-2 text-right">Inflasi MtM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topInflasiMtM as $index => $item)
                                            <tr class="text-xs text-biru1">
                                                <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                                <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                                <td
                                                    class="px-2 py-2 text-right {{ \App\Helpers\InfSpasialHelper::getHeatClass($item->andil, $minAndilMtM, $maxAndilMtM, $inflasiMtM) }}">
                                                    {{-- === TABEL ANGKA UTAMA === --}}
                                                    <span
                                                        class="angka-screenshot">{{ number_format($item->andil, 2, ',', '.') }}</span>
                                                </td>
                                                <td
                                                    class="px-2 py-2 text-right {{ \App\Helpers\InfSpasialHelper::getHeatClass($item->inflasi, $minInflasiMtM, $maxInflasiMtM, $inflasiMtM) }}">
                                                    {{-- === TABEL ANGKA UTAMA === --}}
                                                    <span
                                                        class="angka-screenshot">{{ number_format($item->inflasi, 2, ',', '.') }}</span>
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
                                            <th scope="col" class="px-2 py-2 text-right">Andil YtD</th>
                                            <th scope="col" class="px-2 py-2 text-right">Inflasi YtD</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topInflasiYtD as $index => $item)
                                            <tr class="text-xs text-biru1">
                                                <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                                <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                                <td
                                                    class="px-2 py-2 text-right {{ \App\Helpers\InfSpasialHelper::getHeatClass($item->andil, $minAndilYtD, $maxAndilYtD, $inflasiYtD) }}">
                                                    {{-- === TABEL ANGKA UTAMA === --}}
                                                    <span
                                                        class="angka-screenshot">{{ number_format($item->andil, 2, ',', '.') }}</span>
                                                </td>
                                                <td
                                                    class="px-2 py-2 text-right {{ \App\Helpers\InfSpasialHelper::getHeatClass($item->inflasi, $minInflasiYtD, $maxInflasiYtD, $inflasiYtD) }}">
                                                    {{-- === TABEL ANGKA UTAMA === --}}
                                                    <span
                                                        class="angka-screenshot">{{ number_format($item->inflasi, 2, ',', '.') }}</span>
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
                                            <th scope="col" class="px-2 py-2 text-right">Andil YoY</th>
                                            <th scope="col" class="px-2 py-2 text-right">Inflasi YoY</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topInflasiYoY as $index => $item)
                                            <tr class="text-xs text-biru1">
                                                <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                                <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                                <td
                                                    class="px-2 py-2 text-right {{ \App\Helpers\InfSpasialHelper::getHeatClass($item->andil, $minAndilYoY, $maxAndilYoY, $inflasiYoY) }}">
                                                    {{-- === TABEL ANGKA UTAMA === --}}
                                                    <span
                                                        class="angka-screenshot">{{ number_format($item->andil, 2, ',', '.') }}</span>
                                                </td>
                                                <td
                                                    class="px-2 py-2 text-right {{ \App\Helpers\InfSpasialHelper::getHeatClass($item->inflasi, $minInflasiYoY, $maxInflasiYoY, $inflasiYoY) }}">
                                                    {{-- === TABEL ANGKA UTAMA === --}}
                                                    <span
                                                        class="angka-screenshot">{{ number_format($item->inflasi, 2, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        window.wilayahMap = {};
        @foreach ($daftarKabKota as $wil)
            window.wilayahMap['{{ $wil->kode_wil }}'] = @json($wil->nama_wil);
        @endforeach
        // Untuk leaflet map
        window.wilayahs = @json($wilayahs ?? []);
        window.inflasiWilayah = @json($inflasiWilayah ?? []);
        // Untuk echart barchart
        window.inflasiKomoditasKotaTeratas = @json($inflasiKomoditasKotaTeratas ?? []);
        window.rankingKabKota = @json($rankingKabKota ?? []);
        window.periodeBarchart = '{{ $bulan }} {{ $tahun }}';
        window.jenisDataInflasiBarchart = '{{ $jenisDataInflasi }}';
        // Untuk chart bawah
        window.topAndilMtM = @json($topAndilMtM ?? []);
        window.topAndilYtD = @json($topAndilYtD ?? []);
        window.topAndilYoY = @json($topAndilYoY ?? []);

        // Debug: Check raw data from PHP
        console.log('Raw data from PHP:', {
            topAndilMtM_raw: @json($topAndilMtM ?? []),
            topAndilYtD_raw: @json($topAndilYtD ?? []),
            topAndilYoY_raw: @json($topAndilYoY ?? []),
        });

        // Debug: Log data untuk memastikan tidak kosong
        console.log('Debug Data from Blade:', {
            topAndilMtM: window.topAndilMtM,
            topAndilYtD: window.topAndilYtD,
            topAndilYoY: window.topAndilYoY,
            periodeBarchart: window.periodeBarchart,
            jenisDataInflasiBarchart: window.jenisDataInflasiBarchart
        });

        // Debug: Check if data is actually empty or just not loaded yet
        console.log('Data lengths:', {
            topAndilMtM_length: window.topAndilMtM ? window.topAndilMtM.length : 'undefined',
            topAndilYtD_length: window.topAndilYtD ? window.topAndilYtD.length : 'undefined',
            topAndilYoY_length: window.topAndilYoY ? window.topAndilYoY.length : 'undefined',
        });

        // Debug: Check if data exists at all
        console.log('Data exists check:', {
            topAndilMtM_exists: !!window.topAndilMtM,
            topAndilYtD_exists: !!window.topAndilYtD,
            topAndilYoY_exists: !!window.topAndilYoY,
        });

        // Debug: Check first few items if data exists
        if (window.topAndilMtM && window.topAndilMtM.length > 0) {
            console.log('Sample topAndilMtM data:', window.topAndilMtM.slice(0, 3));
        }
        if (window.topAndilYtD && window.topAndilYtD.length > 0) {
            console.log('Sample topAndilYtD data:', window.topAndilYtD.slice(0, 3));
        }
        if (window.topAndilYoY && window.topAndilYoY.length > 0) {
            console.log('Sample topAndilYoY data:', window.topAndilYoY.slice(0, 3));
        }
        // Data bulan per tahun untuk filter periode
        window.bulanPerTahun = @json($daftarPeriode->groupBy('tahun')->map->pluck('bulan')->toArray());
        // Tambahan agar JS exportExcel bisa akses variabel blade
        window.komoditasUtama = '{{ $komoditasUtama }}';
        window.kabkota = '{{ $kabkota }}';
    </script>
    <script src="{{ asset('js/dashboard/infspasial/tabeldinamis.js') }}"></script>
    <script src="{{ asset('js/dashboard/infspasial/leafletmap.js') }}"></script>
    <script src="{{ asset('js/dashboard/export-png.js') }}"></script>
    <script src="{{ asset('js/dashboard/infspasial/infspasial-consolidated.js') }}"></script>
@endpush
