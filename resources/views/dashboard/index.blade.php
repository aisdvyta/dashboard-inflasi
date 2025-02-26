@extends('layouts.dashboard')

@section('body')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Dashboard Data Inflasi</h2>

        <!-- Filter Periode -->
        <form action="{{ route('dashboard.filter') }}" method="GET">
            <label for="period" class="mr-2">Filter Periode:</label>
            <select name="period" id="period" class="border p-2 rounded">
                @foreach($periods as $period)
                    <option value="{{ $period }}" {{ $selectedPeriod == $period ? 'selected' : '' }}>{{ $period }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Filter</button>
        </form>
    </div>

    @if ($chartData)
        <!-- Chart MtM -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="text-xl font-semibold mb-4">Top 10 Komoditas Inflasi MtM - {{ $selectedPeriod }}</h3>
            <canvas id="inflasiChartMtM"></canvas>
        </div>

        <!-- Chart YTD -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="text-xl font-semibold mb-4">Top 10 Komoditas Inflasi YTD - {{ $selectedPeriod }}</h3>
            <canvas id="inflasiChartYTD"></canvas>
        </div>

        <!-- Chart YoY -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4">Top 10 Komoditas Inflasi YoY - {{ $selectedPeriod }}</h3>
            <canvas id="inflasiChartYoY"></canvas>
        </div>
    @else
        <p class="text-red-500">Tidak ada data tersedia untuk periode {{ $selectedPeriod }}</p>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @if ($chartData)
        const labelsMtM = {!! json_encode(array_column($chartData, 'Nama Komoditas')) !!};
        const dataValuesMtM = {!! json_encode(array_column($chartData, 'Inflasi MtM')) !!};

        new Chart(document.getElementById("inflasiChartMtM"), {
            type: "bar",
            data: {
                labels: labelsMtM,
                datasets: [{
                    label: "Inflasi MtM",
                    data: dataValuesMtM,
                    backgroundColor: "rgba(255, 99, 132, 0.5)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: { x: { beginAtZero: true } }
            }
        });

        // Chart YTD
        const labelsYtD = {!! json_encode(array_column($chartDataYtD, 'Nama Komoditas')) !!};
        const dataValuesYtD = {!! json_encode(array_column($chartDataYtD, 'Inflasi YtD')) !!};

        new Chart(document.getElementById("inflasiChartYtD"), {
            type: "bar",
            data: {
                labels: labelsYtD,
                datasets: [{
                    label: "Inflasi YtD",
                    data: dataValuesYtD,
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: { x: { beginAtZero: true } }
            }
        });

        // Chart YoY
        const labelsYoY = {!! json_encode(array_column($chartDataYoY, 'Nama Komoditas')) !!};
        const dataValuesYoY = {!! json_encode(array_column($chartDataYoY, 'Inflasi YoY')) !!};

        new Chart(document.getElementById("inflasiChartYoY"), {
            type: "bar",
            data: {
                labels: labelsYoY,
                datasets: [{
                    label: "Inflasi YoY",
                    data: dataValuesYoY,
                    backgroundColor: "rgba(75, 192, 192, 0.5)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: { x: { beginAtZero: true } }
            }
        });
    @endif
});
</script>
@endsection
