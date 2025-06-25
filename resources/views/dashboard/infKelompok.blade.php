@extends('layouts.dashboard')

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
                return 'bg-biru5';
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
    $minMtM = $topKelompokMtM->min('inflasi');
    $maxMtM = $topKelompokMtM->max('inflasi');
    $minYtD = $topKelompokYtD->min('inflasi');
    $maxYtD = $topKelompokYtD->max('inflasi');
    $minYoY = $topKelompokYoY->min('inflasi');
    $maxYoY = $topKelompokYoY->max('inflasi');
    $minAndilMtM = $topKelompokMtM->min('andil');
    $maxAndilMtM = $topKelompokMtM->max('andil');
    $minAndilYtD = $topKelompokYtD->min('andil');
    $maxAndilYtD = $topKelompokYtD->max('andil');
    $minAndilYoY = $topKelompokYoY->min('andil');
    $maxAndilYoY = $topKelompokYoY->max('andil');

    $isBlackWhite = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
@endphp

@section('body')
    <div class="mx-auto w-full max-w-7xl">
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
            <div class="flex flex-row flex-wrap gap-6 justify-between pb-6">
                <div class="flex flex-col pl-6">
                    <div class="space-y-1 text-biru1">
                        <h1 class="text-5xl font-bold">Dashboard Inflasi Bulanan</h1>
                        <h1 class="text-5xl font-bold">Menurut <span class="text-kuning1">Kelompok Pengeluaran</span></h1>
                        <h1 class="text-5xl font-bold text-biru4">Wilayah
                            <span
                                class="capitalize">{{ $daftarKabKota->firstWhere('kode_wil', $kabkota)->nama_wil ?? 'Provinsi Jawa Timur' }}</span>
                        </h1>
                    </div>
                    <div class=" {{ $isBlackWhite ? 'grayscale' : '' }}">
                        <div class="flex flex-row gap-4 pt-6 pr-36 text-5xl leading-8 text-biru1">
                            <div class="w-1 h-16 rounded-full opacity-80 bg-biru4"></div>
                            <div>
                                <p class="text-lg opacity-80 text-biru1">Periode Waktu</p>
                                <span class="text-right">{{ $bulan }}</span>
                                <span class="text-right">{{ $tahun }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- filter --}}
                <div class="flex flex-col gap-2 justify-end items-end w-80">
                    <form method="GET" action="{{ route('dashboard.kelompok') }}" class="relative w-full">
                        <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                        <div class="flex relative mb-2 w-full">
                            <div class="relative w-full">
                                <select id="kabkota" name="kabkota"
                                    class="px-6 py-2 w-full font-semibold text-white rounded-full shadow appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    onchange="this.form.submit()">
                                    <option value="">Pilih Kabupaten/Kota</option>
                                    <option value="3500" {{ ($kabkota ?? '') == '3500' ? 'selected' : '' }}>Provinsi
                                        Jawa Timur</option>
                                    @foreach ($daftarKabKota as $kabkotaOption)
                                        <option value="{{ $kabkotaOption->kode_wil }}"
                                            {{ ($kabkota ?? '') == $kabkotaOption->kode_wil ? 'selected' : '' }}>
                                            {{ $kabkotaOption->kode_wil }}. {{ $kabkotaOption->nama_wil }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-6 top-1/2 w-5 h-5 text-white -translate-y-1/2 pointer-events-none"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex relative gap-2 w-full">
                            <div class="relative flex-1">
                                <select id="bulan" name="bulan"
                                    class="px-6 py-2 w-full font-semibold text-white rounded-full shadow appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    onchange="this.form.submit()">
                                    <option value="">Pilih Bulan</option>
                                    @foreach (collect($daftarPeriode)->pluck('bulan')->unique() as $bulanOption)
                                        <option value="{{ $bulanOption }}"
                                            {{ $bulanOption == $bulan ? 'selected' : '' }}>
                                            {{ $bulanOption }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-6 top-1/2 w-5 h-5 text-white -translate-y-1/2 pointer-events-none"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <div class="relative flex-1">
                                <select id="tahun" name="tahun"
                                    class="px-6 py-2 w-full font-semibold text-white rounded-full shadow appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    onchange="this.form.submit()">
                                    <option value="">Pilih Tahun</option>
                                    @foreach (collect($daftarPeriode)->pluck('tahun')->unique() as $tahunOption)
                                        <option value="{{ $tahunOption }}"
                                            {{ $tahunOption == $tahun ? 'selected' : '' }}>
                                            {{ $tahunOption }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-6 top-1/2 w-5 h-5 text-white -translate-y-1/2 pointer-events-none"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 mt-10 mb-4 md:grid-cols-3">
                <div class="flex flex-col gap-4 md:col-span-3">
                    <div class="flex flex-col gap-4 md:flex-row">
                        {{-- MtM --}}
                        <div class="flex flex-col flex-1">
                            <div class="flex overflow-hidden relative flex-col p-0 rounded-2xl shadow-lg">
                                {{-- Bagian atas: label --}}
                                <div class="flex flex-col p-4 items-left bg-biru1">
                                    <div class="text-base font-bold text-white">Inflasi Bulanan (M-to-M, %)</div>
                                    <div class="text-sm italic text-white opacity-80">Nilai inflasi pada Bulan saat ini
                                        terhadap Bulan sebelumnya</div>
                                </div>
                                <div class="border-b border-white opacity-40"></div>
                                {{-- Bagian bawah: badge dan angka utama --}}
                                <div class="relative px-4 pb-4 bg-white rounded-b-2xl">
                                    <div class="absolute top-4 left-4">
                                        @if ($inflasiMtM < 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full shadow">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                Deflasi
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full shadow">
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
                                            class="text-6xl font-extrabold tracking-tight drop-shadow-lg {{ $inflasiMtM < 0 ? 'text-hijau' : 'text-merah2' }}">
                                            {{ number_format($inflasiMtM, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- YtD --}}
                        <div class="flex flex-col flex-1 gap-2">
                            <div class="flex overflow-hidden relative flex-col p-0 rounded-2xl shadow-lg">
                                {{-- Bagian atas: label --}}
                                <div class="flex flex-col p-4 items-left bg-biru1">
                                    <div class="text-base font-bold text-white">Inflasi Tahun Kalender (Y-to-D, %)</div>
                                    <div class="text-sm italic text-white opacity-80">Nilai inflasi pada Bulan saat ini
                                        terhadap Bulan Desember Tahun sebelumnya</div>
                                    {{-- <span class="text-xs italic font-normal text-white">(Y-to-D, %)</span> --}}
                                </div>
                                <div class="border-b border-white opacity-40"></div>
                                {{-- Bagian bawah: badge dan angka utama --}}
                                <div class="relative px-4 pb-4 bg-white rounded-b-2xl">
                                    <div class="absolute top-4 left-4">
                                        @if ($inflasiYtD < 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full shadow">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                Deflasi
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full shadow">
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
                                            class="text-6xl font-extrabold tracking-tight drop-shadow-lg {{ $inflasiYtD < 0 ? 'text-hijau' : 'text-merah2' }}">
                                            {{ number_format($inflasiYtD, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- YoY --}}
                        <div class="flex flex-col flex-1 gap-2">
                            <div class="flex overflow-hidden relative flex-col p-0 rounded-2xl shadow-lg">
                                {{-- Bagian atas: label --}}
                                <div class="flex flex-col p-4 items-left bg-biru1">
                                    <div class="text-base font-bold text-white">Inflasi Tahunan (Y-to-Y, %)</div>
                                    <div class="text-sm italic text-white opacity-80">Nilai inflasi pada Bulan ini di Tahun
                                        saat ini terhadap Bulan ini di Tahun sebelumnya</div>
                                    {{-- <span class="text-xs italic font-normal text-white">(Y-to-Y, %)</span> --}}
                                </div>
                                <div class="border-b border-white opacity-40"></div>
                                {{-- Bagian bawah: badge dan angka utama --}}
                                <div class="relative px-4 pb-4 bg-white rounded-b-2xl">
                                    <div class="absolute top-4 left-4">
                                        @if ($inflasiYoY < 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full shadow">
                                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                                Deflasi
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full shadow">
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
                                            class="text-6xl font-extrabold tracking-tight drop-shadow-lg {{ $inflasiYoY < 0 ? 'text-hijau' : 'text-merah2' }}">
                                            {{ number_format($inflasiYoY, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4 pt-4 md:flex-row">
                {{-- tabel inflasi kelompok pengeluaran --}}
                <div class="relative w-full md:w-7/12">
                    <div class="{{ $isBlackWhite ? 'grayscale' : '' }}">
                        <h1 class="text-lg font-bold text-biru1">Tabel Inflasi Bulanan Menurut Kelompok Pengeluaran
                        </h1>
                    </div>
                    <div class="overflow-x-auto overflow-y-auto max-h-[420px] rounded-xl shadow border border-biru1 mt-4">
                        <table class="text-sm text-left">
                            <thead class="sticky top-0 z-10 text-white bg-biru1">
                                <tr>
                                    <th class="px-3 py-2 font-bold">Kode Komoditas</th>
                                    <th class="px-3 py-2 font-bold">Nama Komoditas</th>
                                    <th class="px-3 py-2 font-bold">Inflasi MtM</th>
                                    <th class="px-3 py-2 font-bold">Andil MtM</th>
                                    <th class="px-3 py-2 font-bold">Inflasi YtD</th>
                                    <th class="px-3 py-2 font-bold">Andil YtD</th>
                                    <th class="px-3 py-2 font-bold">Inflasi YoY</th>
                                    <th class="px-3 py-2 font-bold">Andil YoY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tabelKelompok as $row)
                                    <tr class="{{ $loop->even ? 'bg-blue-100' : 'bg-white' }} text-biru1">
                                        <td class="px-3 py-2">{{ $row->kode_kom }}</td>
                                        <td class="px-3 py-2">{{ $row->nama_kom }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($row->inflasi_mtm, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($row->andil_mtm, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($row->inflasi_ytd, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($row->andil_ytd, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($row->inflasi_yoy, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($row->andil_yoy, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- barchartnya --}}
                <div class="flex relative flex-col w-full md:w-5/12">
                    <h2 class="mb-4 text-lg font-bold text-biru1">Andil Inflasi Menurut Kelompok Pengeluaran (%)
                    </h2>
                    <div class="flex flex-col p-6 h-full bg-white rounded-2xl border shadow border-biru1">
                        <div id="andilKelompokBar" class=" h-[350px]"></div>
                    </div>
                </div>
            </div>

            {{-- tabel lima komoditas teratas --}}
            <div>
                <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <h1 class="text-xl font-bold text-biru1">Tabel <span class="text-biru4">Lima Komoditas
                            Teratas</span>
                        Menurut Kelompok Pengeluaran <i>(M-to-M)</i></h1>
                </div>
                <div class="overflow-x-auto overflow-y-auto max-h-[420px] rounded-xl shadow border border-biru1 mt-4">
                    <table class="text-sm text-left">
                        <thead class="sticky top-0 z-10 text-white bg-biru1">
                            <tr>
                                <th class="px-3 py-2 w-1/6 font-bold">Nama kelompok</th>
                                <th class="px-3 py-2 w-1/4 font-bold">Top 5 - Nama Komoditas</th>
                                <th class="px-3 py-2 font-bold">Inflasi MtM</th>
                                <th class="px-3 py-2 font-bold">Andil MtM</th>
                                <th class="px-3 py-2 font-bold">Inflasi YtD</th>
                                <th class="px-3 py-2 font-bold">Andil YtD</th>
                                <th class="px-3 py-2 font-bold">Inflasi YoY</th>
                                <th class="px-3 py-2 font-bold">Andil YoY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelompokUtama as $kelompok)
                                @php $top5 = $top5KomoditasPerKelompok[$kelompok->kode_kom] ?? []; @endphp
                                @foreach ($top5 as $i => $komoditas)
                                    <tr class="font-semibold bg-blue-100 text-biru1">
                                        @if ($i === 0)
                                            <td class="px-3 py-2 font-semibold align-top" rowspan="{{ count($top5) }}">
                                                {{ $kelompok->kode_kom }}. {{ $kelompok->nama_kom }}</td>
                                        @endif
                                        <td class="px-3 py-2">{{ $komoditas->nama_kom }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->inflasi_mtm, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->andil_mtm, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->inflasi_ytd, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->andil_ytd, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->inflasi_yoy, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->andil_yoy, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data chart dari PHP
            const kelompokData = @json($topKelompokMtM);
            const labels = kelompokData.map(item => item.nama_kelompok);
            const values = kelompokData.map(item => item.andil);

            const chartDom = document.getElementById('andilKelompokBar');
            if (chartDom) {
                const myChart = echarts.init(chartDom);
                const option = {
                    grid: {
                        left: 120,
                        right: 40,
                        top: 20,
                        bottom: 30
                    },
                    xAxis: {
                        type: 'value',
                        axisLabel: {
                            fontWeight: 'semibold',
                            color: '#3B4A6B'
                        },
                        splitLine: {
                            show: false
                        }
                    },
                    yAxis: {
                        type: 'category',
                        data: labels,
                        axisLabel: {
                            fontWeight: 'semibold',
                            color: '#3B4A6B',
                            fontSize: 16
                        },
                        axisTick: {
                            show: false
                        }
                    },
                    series: [{
                        type: 'bar',
                        data: values,
                        label: {
                            show: true,
                            position: 'right',
                            fontWeight: 'semibold',
                            fontSize: 16,
                            color: '#3B4A6B',
                            formatter: function(params) {
                                return params.value.toFixed(2);
                            }
                        },
                        itemStyle: {
                            color: '#3B4A6B',
                            borderRadius: [0, 8, 8, 0]
                        },
                        barWidth: 24
                    }]
                };
                myChart.setOption(option);
                window.addEventListener('resize', () => myChart.resize());
            }
        });
    </script>
@endpush
