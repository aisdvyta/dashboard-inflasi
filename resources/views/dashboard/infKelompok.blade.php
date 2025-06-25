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
    <div class="container mx-auto">
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
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="flex flex-col gap-4">
                    <div class="space-y-1">
                        <h1 class="text-5xl font-bold md:text-5xl text-biru1">DASHBOARD</h1>
                        <h1 class="text-5xl font-bold md:text-5xl text-biru4">INFLASI BULANAN</h1>
                        <h1 class="text-4xl font-bold text-biru1">Menurut <span class="text-biru4">Kelompok Pengeluaran</span></h1>
                    </div>
                    <div class="bg-biru1 text-white rounded-2xl flex flex-col items-center justify-center py-8 px-6 mt-4 mb-2">
                        <div class="text-3xl font-bold mb-2 uppercase">{{ $daftarKabKota->firstWhere('kode_wil', $kabkota)->nama_wil ?? 'Provinsi Jawa Timur' }}</div>
                        <div class="flex flex-row gap-12 text-2xl font-semibold">
                            <span>{{ $bulan }}</span>
                            <span>{{ $tahun }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 w-full">
                        <form method="GET" action="{{ route('dashboard.kelompok') }}" class="flex flex-col gap-2 w-full">
                            <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                            <div class="flex w-full mb-2">
                                <div class="relative flex-1">
                                    <select id="kabkota" name="kabkota"
                                        class="w-full px-6 py-2 font-semibold text-white rounded-full shadow bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400 appearance-none"
                                        onchange="this.form.submit()">
                                        <option value="" disabled selected>Kota: {{ $kabkota }}. {{ $daftarKabKota->firstWhere('kode_wil', $kabkota)->nama_wil ?? 'Provinsi Jawa Timur' }}</option>
                                        <option value="3500" {{ ($kabkota ?? '') == '3500' ? 'selected' : '' }}>Provinsi Jawa Timur</option>
                                        @foreach ($daftarKabKota as $kabkotaOption)
                                            <option value="{{ $kabkotaOption->kode_wil }}" {{ ($kabkota ?? '') == $kabkotaOption->kode_wil ? 'selected' : '' }}>
                                                {{ $kabkotaOption->kode_wil }}. {{ $kabkotaOption->nama_wil }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="absolute right-6 top-1/2 -translate-y-1/2 w-5 h-5 text-white pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <div class="flex gap-2 w-full">
                                <div class="relative flex-1">
                                    <select id="bulan" name="bulan"
                                        class="w-full px-6 py-2 font-semibold text-white rounded-full shadow bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400 appearance-none"
                                        onchange="this.form.submit()">
                                        <option value="" disabled selected>Bulan: {{ $bulan }}</option>
                                        @foreach (collect($daftarPeriode)->pluck('bulan')->unique() as $bulanOption)
                                            <option value="{{ $bulanOption }}" {{ $bulanOption == $bulan ? 'selected' : '' }}>
                                                {{ $bulanOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="absolute right-6 top-1/2 -translate-y-1/2 w-5 h-5 text-white pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                <div class="relative flex-1">
                                    <select id="tahun" name="tahun"
                                        class="w-full px-6 py-2 font-semibold text-white rounded-full shadow bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400 appearance-none"
                                        onchange="this.form.submit()">
                                        <option value="" disabled selected>Tahun: {{ $tahun }}</option>
                                        @foreach (collect($daftarPeriode)->pluck('tahun')->unique() as $tahunOption)
                                            <option value="{{ $tahunOption }}" {{ $tahunOption == $tahun ? 'selected' : '' }}>
                                                {{ $tahunOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="absolute right-6 top-1/2 -translate-y-1/2 w-5 h-5 text-white pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow flex flex-col p-6 h-full border border-biru1">
                    <h2 class="text-xl font-bold text-biru1 mb-4 text-center">Andil Inflasi Menurut Kelompok Pengeluaran (%)</h2>
                    <div id="andilKelompokBar" class="w-full h-[350px]"></div>
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
            <div class="my-8">
                <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <h1 class="text-3xl font-bold text-biru1">Tabel Inflasi Bulanan</h1>
                    <h1 class="text-2xl font-bold text-biru1">Menurut <span class="text-biru4">Kelompok Pengeluaran</span></h1>
                </div>
                <div class="overflow-x-auto overflow-y-auto max-h-[420px] rounded-xl shadow border border-biru1 mt-4">
                    <table class="min-w-full text-sm text-left">
                        <thead class="sticky top-0 z-10 bg-biru1 text-white">
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
                                    <td class="px-3 py-2 text-right">{{ number_format($row->inflasi_mtm, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($row->andil_mtm, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($row->inflasi_ytd, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($row->andil_ytd, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($row->inflasi_yoy, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($row->andil_yoy, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                    <h1 class="text-3xl font-bold text-biru1">Tabel <span class="text-biru4">Lima Komoditas Teratas</span></h1>
                    <h1 class="text-2xl font-bold text-biru1">Menurut Kelompok Pengeluaran <i>(M-to-M)</i></h1>
                </div>
                <div class="overflow-x-auto overflow-y-auto max-h-[420px] rounded-xl shadow border border-biru1 mt-4">
                    <table class="min-w-full text-sm text-left">
                        <thead class="sticky top-0 z-10 bg-biru1 text-white">
                            <tr>
                                <th class="px-3 py-2 font-bold w-1/6">Nama kelompok</th>
                                <th class="px-3 py-2 font-bold w-1/4">Top 5 - Nama Komoditas</th>
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
                                    <tr class="bg-blue-100 text-biru1 font-semibold">
                                        @if ($i === 0)
                                            <td class="px-3 py-2 align-top font-semibold" rowspan="{{ count($top5) }}">{{ $kelompok->kode_kom }}. {{ $kelompok->nama_kom }}</td>
                                        @endif
                                        <td class="px-3 py-2">{{ $komoditas->nama_kom }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($komoditas->inflasi_mtm, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($komoditas->andil_mtm, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($komoditas->inflasi_ytd, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($komoditas->andil_ytd, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($komoditas->inflasi_yoy, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($komoditas->andil_yoy, 2, ',', '.') }}</td>
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
                    grid: { left: 120, right: 40, top: 20, bottom: 30 },
                    xAxis: {
                        type: 'value',
                        axisLabel: { fontWeight: 'bold', color: '#3B4A6B' },
                        splitLine: { show: false }
                    },
                    yAxis: {
                        type: 'category',
                        data: labels,
                        axisLabel: { fontWeight: 'bold', color: '#3B4A6B', fontSize: 16 },
                        axisTick: { show: false }
                    },
                    series: [
                        {
                            type: 'bar',
                            data: values,
                            label: {
                                show: true,
                                position: 'right',
                                fontWeight: 'bold',
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
                        }
                    ]
                };
                myChart.setOption(option);
                window.addEventListener('resize', () => myChart.resize());
            }
        });
    </script>
@endpush
