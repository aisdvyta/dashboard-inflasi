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
        <h1 class="text-4xl font-bold text-biru1 mb-2">DASHBOARD <span class="text-biru4">SERIES INFLASI</span></h1>
        <div class="flex flex-row gap-4 items-center mb-6">
            <form method="GET" action="{{ route('dashboard.series') }}" class="flex gap-2">
                <select name="komoditas" class="rounded px-3 py-2 border" onchange="this.form.submit()">
                    @foreach($daftarKomoditasUtama as $kom)
                        <option value="{{ $kom }}" {{ $kom == $komoditas ? 'selected' : '' }}>{{ $kom }}</option>
                    @endforeach
                </select>
                <select name="tahun" class="rounded px-3 py-2 border" onchange="this.form.submit()">
                    @foreach($tahunList as $th)
                        <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="grid grid-cols-1 gap-8">
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
    </div>
@endsection

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/dashboard/infBulananJatim.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script>
    const seriesData = @json($seriesData);

    function renderChart(domId, barData, lineData, yBarName, yLineName) {
        var chartDom = document.getElementById(domId);
        var myChart = echarts.init(chartDom);
        var option = {
            tooltip: { trigger: 'axis', axisPointer: { type: 'cross' } },
            legend: { data: [yBarName, yLineName] },
            xAxis: [{ type: 'category', data: seriesData.bulan, axisPointer: { type: 'shadow' } }],
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
    </script>
@endpush
