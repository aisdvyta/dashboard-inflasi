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
                        <a href="{{ route('dashboard.series', array_merge(request()->except('jenis_data_inflasi'), ['jenis_data_inflasi' => $tab])) }}"
                            class="tab-link flex items-center px-14 py-2 transition-all duration-300 rounded-t-xl {{ $jenisDataInflasi === $tab ? 'bg-biru1 text-white' : 'bg-biru4 text-white' }} hover:bg-biru1 group"
                            data-tab="{{ $tab }}" id="tab-{{ strtolower(str_replace(' ', '-', $tab)) }}">
                            <span class="menu-text text-[15px] font-medium transition duration-100">
                                {{ $tab }}
                            </span>
                        </a>
                    @endforeach
                @endauth
                @guest
                    <a href="{{ route('dashboard.series', array_merge(request()->except('jenis_data_inflasi'), ['jenis_data_inflasi' => 'ATAP'])) }}"
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
            <h1 class="text-4xl font-bold text-biru1 mb-2">DASHBOARD <span class="text-biru4">SERIES INFLASI</span></h1>
        <div class="flex flex-row gap-4 items-center mb-6">
            <form method="GET" action="{{ route('dashboard.series') }}" class="flex gap-2" id="filterForm">
                <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                <select name="komoditas" class="rounded px-3 py-2 border" onchange="this.form.submit()">
                    @foreach($daftarKomoditasUtama as $kom)
                        <option value="{{ $kom }}" {{ $kom == $komoditas ? 'selected' : '' }}>{{ $kom }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="flex flex-row gap-8">
            <div class="flex-1 grid grid-cols-1 gap-8">
                <div>
                    <h2 class="font-semibold mb-2">Series Nilai Inflasi Bulanan dalam satu tahun (M-to-M, %)</h2>
                    <div id="chart-mtm" style="height:350px;"></div>
                </div>
                <div>
                    <h2 class="font-semibold mb-2">Series Nilai Inflasi Tahun Kalender dalam satu tahun (Y-to-D, %)</h2>
                    <div id="chart-ytd" style="height:350px;"></div>
                </div>
                <div>
                    <h2 class="font-semibold mb-2">Series Nilai Inflasi Tahunan dalam satu tahun (Y-to-Y, %)</h2>
                    <div id="chart-yoy" style="height:350px;"></div>
                </div>
            </div>
            <form method="GET" action="{{ route('dashboard.series') }}" class="w-64 p-4 bg-gray-50 rounded-xl shadow flex flex-col gap-4 h-fit self-start" id="filterFormSide">
                <input type="hidden" name="komoditas" value="{{ $komoditas }}">
                <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                <div>
                    <label class="font-bold">Tahun</label><br>
                    <div class="flex flex-col ml-2">
                        @foreach($tahunList as $th)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="tahun_check[]" value="{{ $th }}" class="form-checkbox tahun-check" {{ (request()->has('tahun_check') && in_array($th, request('tahun_check', []))) ? 'checked' : '' }}>
                                <span class="ml-1">{{ $th }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @foreach($tahunBulanList as $th => $bulanArr)
                    <div class="bulan-group" data-tahun="{{ $th }}" style="display: {{ (!request()->has('tahun_check') || in_array($th, request('tahun_check', []))) ? 'block' : 'none' }};">
                        <label class="font-bold">Bulan pada {{ $th }}</label><br>
                        <div class="flex flex-col ml-2">
                            @foreach($bulanArr as $bln)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="tahun_bulan[]" value="{{ $th }}-{{ $bln }}" class="form-checkbox bulan-check" {{ (request()->has('tahun_bulan') && in_array($th.'-'.$bln, request('tahun_bulan', []))) ? 'checked' : '' }}>
                                    <span class="ml-1">{{ $bln }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </form>
        </div>
        <script>
        // Tampilkan/hilangkan bulan sesuai tahun yang dichecklist dan auto-submit form jika ada perubahan
        document.addEventListener('DOMContentLoaded', function() {
            const tahunChecks = document.querySelectorAll('#filterFormSide .tahun-check');
            const bulanChecks = document.querySelectorAll('#filterFormSide .bulan-check');
            const form = document.getElementById('filterFormSide');
            tahunChecks.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    document.querySelectorAll('#filterFormSide .bulan-group').forEach(function(bg) {
                        const tahun = bg.getAttribute('data-tahun');
                        if (document.querySelector('#filterFormSide input.tahun-check[value="'+tahun+'"]:checked')) {
                            bg.style.display = 'block';
                        } else {
                            bg.style.display = 'none';
                        }
                    });
                    form.submit();
                });
            });
            bulanChecks.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    form.submit();
                });
            });
        });
        </script>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/dashboard/infBulananJatim.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script>
    const seriesData = @json($seriesData);

    // Format bulan-tahun ke "Jan-24" dst
    function shortMonthYearLabel(bulanArr) {
        const monthMap = {
            'Januari': 'Jan', 'February': 'Feb', 'Februari': 'Feb', 'Maret': 'Mar', 'March': 'Mar', 'April': 'Apr', 'Mei': 'Mei', 'May': 'Mei', 'Juni': 'Jun', 'June': 'Jun', 'Juli': 'Jul', 'July': 'Jul', 'Agustus': 'Agu', 'August': 'Agu', 'September': 'Sep', 'Oktober': 'Okt', 'October': 'Okt', 'November': 'Nov', 'Desember': 'Des', 'December': 'Des'
        };
        // Ambil tahun dari data (seriesData.bulan bisa "Februari", "Maret", dst, urut sesuai data)
        // Asumsi: seriesData.bulan urut sesuai data, dan tahun diambil dari tahun filter aktif
        // Untuk robust, backend bisa kirim array tahun juga, tapi di sini kita asumsikan tahun sama/urut
        let tahunAktif = @json(request('tahun_check', [$tahun]));
        let tahunBulanAktif = @json(request('tahun_bulan', []));
        // Buat array tahun sesuai urutan bulan
        let tahunArr = [];
        if (tahunBulanAktif.length === bulanArr.length) {
            tahunArr = tahunBulanAktif.map(tb => tb.split('-')[0].slice(-2));
        } else {
            tahunArr = Array(bulanArr.length).fill(tahunAktif[0] ? tahunAktif[0].slice(-2) : '');
        }
        return bulanArr.map((b, i) => {
            let m = monthMap[b] || b.slice(0,3);
            let th = tahunArr[i] || '';
            return th ? `${m}-${th}` : m;
        });
    }

    function renderChart(domId, barData, lineData, yBarName, yLineName) {
        var chartDom = document.getElementById(domId);
        var myChart = echarts.init(chartDom);
        var option = {
            tooltip: { trigger: 'axis', axisPointer: { type: 'cross' } },
            legend: { data: [yBarName, yLineName] },
            xAxis: [{ type: 'category', data: shortMonthYearLabel(seriesData.bulan), axisPointer: { type: 'shadow' } }],
            yAxis: [
                { type: 'value', name: yBarName, axisLabel: { formatter: '{value}' } },
                { type: 'value', name: yLineName, axisLabel: { formatter: '{value}' } }
            ],
            series: [
                { name: yBarName, type: 'bar', data: barData },
                { name: yLineName, type: 'line', yAxisIndex: 1, data: lineData }
            ]
        };
        myChart.setOption(option);
    }

    renderChart('chart-mtm', seriesData.andil_mtm, seriesData.inflasi_mtm, 'Andil MtM', 'Inflasi MtM');
    renderChart('chart-ytd', seriesData.andil_ytd, seriesData.inflasi_ytd, 'Andil YtD', 'Inflasi YtD');
    renderChart('chart-yoy', seriesData.andil_yoy, seriesData.inflasi_yoy, 'Andil YoY', 'Inflasi YoY');

    // Simpan dan restore filter tahun/bulan di localStorage GLOBAL (bukan per komoditas)
    document.addEventListener('DOMContentLoaded', function() {
        const komoditasSelect = document.querySelector('select[name="komoditas"]');
        const tahunChecks = document.querySelectorAll('#filterFormSide .tahun-check');
        const bulanChecks = document.querySelectorAll('#filterFormSide .bulan-check');
        const formSide = document.getElementById('filterFormSide');
        let restored = false;
        // Key localStorage per jenisDataInflasi
        const jenisDataInflasi = @json($jenisDataInflasi);
        const filterKey = 'filter_global_' + jenisDataInflasi;
        // Restore per jenis inflasi
        if (formSide) {
            let saved = localStorage.getItem(filterKey);
            if (saved) {
                try {
                    let obj = JSON.parse(saved);
                    tahunChecks.forEach(cb => cb.checked = false);
                    bulanChecks.forEach(cb => cb.checked = false);
                    if (obj.tahun_check) {
                        obj.tahun_check.forEach(val => {
                            let cb = formSide.querySelector('input.tahun-check[value="'+val+'"]');
                            if (cb) cb.checked = true;
                        });
                    }
                    if (obj.tahun_bulan) {
                        obj.tahun_bulan.forEach(val => {
                            let cb = formSide.querySelector('input.bulan-check[value="'+val+'"]');
                            if (cb) cb.checked = true;
                        });
                    }
                    let tahunNow = Array.from(formSide.querySelectorAll('input.tahun-check:checked')).map(cb => cb.value);
                    let bulanNow = Array.from(formSide.querySelectorAll('input.bulan-check:checked')).map(cb => cb.value);
                    let tahunReq = @json(request('tahun_check', []));
                    let bulanReq = @json(request('tahun_bulan', []));
                    if (JSON.stringify(tahunNow) !== JSON.stringify(tahunReq) || JSON.stringify(bulanNow) !== JSON.stringify(bulanReq)) {
                        restored = true;
                        formSide.submit();
                    }
                } catch(e) {}
            }
        }
        // Save on change (per jenis inflasi)
        [tahunChecks, bulanChecks, komoditasSelect].forEach(list => {
            if (!list) return;
            (list.length ? list : [list]).forEach(el => {
                el.addEventListener('change', function() {
                    let tahunVals = Array.from(formSide.querySelectorAll('input.tahun-check:checked')).map(cb => cb.value);
                    let bulanVals = Array.from(formSide.querySelectorAll('input.bulan-check:checked')).map(cb => cb.value);
                    localStorage.setItem(filterKey, JSON.stringify({tahun_check: tahunVals, tahun_bulan: bulanVals}));
                });
            });
        });
    });

    // Reset filter tahun/bulan & komoditas ke default saat ganti jenis inflasi
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.tab-link').forEach(function(tab) {
            tab.addEventListener('click', function(e) {
                // Hapus filter tahun/bulan & komoditas dari localStorage
                const jenis = this.getAttribute('data-tab');
                localStorage.removeItem('filter_global_' + jenis);
                // Redirect tanpa query tahun_bulan, tahun_check, komoditas
                e.preventDefault();
                let url = new URL(this.href, window.location.origin);
                url.searchParams.delete('tahun_bulan');
                url.searchParams.delete('tahun_check');
                url.searchParams.delete('komoditas');
                window.location.href = url.toString();
            });
        });
    });
    </script>
@endpush
