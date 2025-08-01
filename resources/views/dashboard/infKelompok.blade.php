@extends('layouts.dashboard')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/dashboard/infkelompok.css') }}" />
@endpush

@php
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
    $maxInflasi = $tabelKelompok->max('inflasi_mtm');
    $minInflasi = $tabelKelompok->min('inflasi_mtm');

    $isBlackWhite = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
@endphp

@section('body')
    <div>
        <x-dash-header
            :tabs="['ASEM 1', 'ASEM 2', 'ASEM 3', 'ATAP']"
            :activeTab="$jenisDataInflasi"
            routeName="dashboard.kelompok"
            :routeParams="['bulan' => $bulan, 'tahun' => $tahun, 'kabkota' => $kabkota]"
            :showDropdown="false"
            :showExcel="true"
            :showPng="true"
            exportExcelId="exportExcelKelompok"
            exportPngId="exportPNG"
            class="mb-4"
        />
    </div>

    <div class="border-t-8 border-biru1">
        <div id="main-dashboard-content" class="p-6 bg-white rounded-b-xl shadow-md {{ $isBlackWhite ? 'grayscale' : '' }}">
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
                                @php
                                    $user = Auth::user();
                                    $isKabkot = $user && $user->id_role == 2;
                                    $isAsem = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
                                    $shouldRestrict = $isKabkot && $isAsem;
                                @endphp

                                @if($shouldRestrict && $daftarKabKota->count() == 1)
                                    {{-- Show as text when only one option --}}
                                    <div class="px-6 py-2 w-full font-semibold text-white rounded-full shadow bg-biru4">
                                        {{ $daftarKabKota->first()->nama_wil }}
                                    </div>
                                    <input type="hidden" name="kabkota" value="{{ $daftarKabKota->first()->kode_wil }}">
                                @else
                                    <select id="kabkota" name="kabkota"
                                        class="px-6 py-2 w-full font-semibold text-white rounded-full shadow appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                        onchange="this.form.submit()">
                                        @if(!$shouldRestrict)
                                            <option value="">Pilih Kabupaten/Kota</option>
                                            <option value="3500" {{ ($kabkota ?? '') == '3500' ? 'selected' : '' }}>Provinsi
                                                Jawa Timur</option>
                                        @endif
                                        @foreach ($daftarKabKota as $kabkotaOption)
                                            <option value="{{ $kabkotaOption->kode_wil }}"
                                                {{ ($kabkota ?? '') == $kabkotaOption->kode_wil ? 'selected' : '' }}>
                                                {{ $kabkotaOption->nama_wil }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="absolute right-6 top-1/2 w-5 h-5 text-white -translate-y-1/2 pointer-events-none"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
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
                                    <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan saat
                                        ini
                                        terhadap Bulan sebelumnya</div>
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
                            <div class="flex overflow-hidden relative flex-col p-0 rounded-2xl shadow-lg">
                                {{-- Bagian atas: label --}}
                                <div class="flex flex-col p-4 items-left bg-biru1">
                                    <div class="text-base font-bold text-white">Inflasi Tahun Kalender (Y-to-D, %)</div>
                                    <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan saat
                                        ini
                                        terhadap Bulan Desember Tahun sebelumnya</div>
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
                            <div class="flex overflow-hidden relative flex-col p-0 rounded-2xl shadow-lg">
                                {{-- Bagian atas: label --}}
                                <div class="flex flex-col p-4 items-left bg-biru1">
                                    <div class="text-base font-bold text-white">Inflasi Tahunan (Y-to-Y, %)</div>
                                    <div class="text-xs italic text-white opacity-80">Perubahan nilai IHK pada Bulan ini di
                                        Tahun
                                        saat ini terhadap Bulan ini di Tahun sebelumnya</div>
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
            </div>

            <div class="flex flex-col gap-4 pt-4 md:flex-row">
                {{-- tabel inflasi kelompok pengeluaran --}}
                <div class="relative w-full md:w-7/12">
                    <div class="{{ $isBlackWhite ? 'grayscale' : '' }}">
                        <h1 class="text-lg font-bold text-biru1">Tabel Inflasi Bulanan Menurut Kelompok Pengeluaran
                        </h1>
                    </div>
                    <div class="overflow-x-auto overflow-y-auto max-h-[500px] rounded-xl shadow border border-biru1 mt-2">
                        <table class="text-sm text-left">
                            <thead class="sticky top-0 z-10 text-white bg-biru1">
                                <tr>
                                    <th class="px-3 py-1 font-semibold">Kelompok Pengeluaran</th>
                                    <th class="px-3 py-1 font-semibold">Inflasi MtM</th>
                                    <th class="px-3 py-1 font-semibold">Andil MtM</th>
                                    <th class="px-3 py-1 font-semibold">Inflasi YtD</th>
                                    <th class="px-3 py-1 font-semibold">Andil YtD</th>
                                    <th class="px-3 py-1 font-semibold">Inflasi YoY</th>
                                    <th class="px-3 py-1 font-semibold">Andil YoY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tabelKelompok as $row)
                                    @php
                                        $isUmum = strtolower(trim($row->nama_kom)) === 'umum';
                                        $isMax = $row->inflasi_mtm == $maxInflasi;
                                        $isMin = $row->inflasi_mtm == $minInflasi;
                                        $rowClass = '';
                                        if ($isUmum) {
                                            $rowClass .= ' font-bold';
                                            $rowClass .= ' bg-abubiru';
                                        }
                                        if ($isMax) {
                                            $rowClass .= ' bg-red-300';
                                        }
                                        if ($isMin) {
                                            $rowClass .= ' bg-hijau2';
                                        }
                                    @endphp
                                    <tr class="text-biru1 capitalize{{ $rowClass }}">
                                        <td class="px-3 py-2">
                                            {{ ucwords(strtolower($row->nama_kom)) }}
                                        </td>
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
                    <h2 class="mb-2 text-lg font-bold text-biru1">Andil Inflasi Menurut Kelompok Pengeluaran (%)
                    </h2>
                    <div class="flex flex-col p-6 h-full bg-white rounded-2xl border shadow border-biru1">
                        <div id="andilKelompokBar" class="w-full h-full"></div>
                    </div>
                </div>
            </div>

            {{-- tabel lima komoditas teratas --}}
            <div>
                <div class="space-y-1   {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <h1 class="text-xl font-bold text-biru1 pt-6">Tabel Lima Komoditas
                            Teratas Menurut Kelompok Pengeluaran <i>(M-to-M)</i></h1>
                </div>
                <div class="overflow-x-auto overflow-y-auto max-h-[420px] rounded-xl shadow border border-biru1 mt-2">
                    <table class="text-sm text-left">
                        <thead class="sticky top-0 z-10 text-white bg-biru1">
                            <tr>
                                <th class="px-3 py-2 w-1/6 font-semibold">Nama kelompok</th>
                                <th class="px-3 py-2 w-1/4 font-semibold">5 Komoditas Tertinggi</th>
                                <th class="px-3 py-2 font-semibold">Inflasi MtM</th>
                                <th class="px-3 py-2 font-semibold">Andil MtM</th>
                                <th class="px-3 py-2 font-semibold">Inflasi YtD</th>
                                <th class="px-3 py-2 font-semibold">Andil YtD</th>
                                <th class="px-3 py-2 font-semibold">Inflasi YoY</th>
                                <th class="px-3 py-2 font-semibold">Andil YoY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelompokUtama as $kelompok)
                                @php $top5 = $top5KomoditasPerKelompok[$kelompok->kode_kom] ?? []; @endphp
                                @foreach ($top5 as $i => $komoditas)
                                    <tr class="text-biru1{{ $i === 0 ? ' border-t border-gray-300' : '' }}">
                                        @if ($i === 0)
                                            <td class="px-3 py-2 font-semibold align-top" rowspan="{{ count($top5) }}">
                                                {{ ucwords(strtolower($kelompok->nama_kom)) }}
                                            </td>
                                        @endif
                                        <td class="px-3 py-2">{{ ucwords(strtolower($komoditas->nama_kom)) }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->inflasi_mtm, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->andil_mtm, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->inflasi_ytd, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->andil_ytd, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->inflasi_yoy, 2, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            {{ number_format($komoditas->andil_yoy, 2, ',', '.') }}
                                        </td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="{{ asset('js/dashboard/export-png.js') }}"></script>
    <script>
        // Push variabel blade ke window agar bisa diakses JS eksternal
        window.tabelKelompok = @json($tabelKelompok);
        window.top5KomoditasPerKelompok = @json($top5KomoditasPerKelompok);
        window.jenisDataInflasi = "{{ $jenisDataInflasi }}";
        window.kabkota = "{{ $kabkota }}";
        window.bulan = "{{ $bulan }}";
        window.tahun = "{{ $tahun }}";
    </script>
    <script src="{{ asset('js/dashboard/infkelompok-page.js') }}"></script>
@endpush
