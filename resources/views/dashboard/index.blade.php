@extends('layouts.main')

@section('body')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">Dashboard Data Inflasi</h2>

    @foreach ($chartsData as $index => $chart)
        <div class="bg-white shadow-md rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold">{{ $chart['data_name'] }}</h3>
            <canvas id="chart-{{ $index }}" class="mt-4"></canvas>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var ctx = document.getElementById("chart-{{ $index }}").getContext("2d");

                var labels = {!! json_encode(array_column($chart['data'], 'nama_kota')) !!};
                var data = {!! json_encode(array_column($chart['data'], 'ihk')) !!};

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "IHK by Kota (Flag = 0)",
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        </script>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
