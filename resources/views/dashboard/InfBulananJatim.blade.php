@extends('layouts.dashboard')
@section('body')
    <h1>Ini Dashboard Bulanan Jatim</h1>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptas quidem est modi quo illo ab nobis, voluptatum porro, fuga consequuntur vel autem earum quia, dignissimos accusamus ut nesciunt! Dolore, fuga. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptas quidem est modi quo illo ab nobis, voluptatum porro, fuga consequuntur vel autem earum quia, dignissimos accusamus ut nesciunt! Dolore, fuga.  </p>
    <div id="main" class="w-auto h-96"></div>
    <div id="main2" class="w-auto h-96"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chartDom = document.getElementById("main"); //ini itu inject ke id main
            var myChart = echarts.init(chartDom);
            var option = {
                title: {
                    text: "ECharts example",
                },
                tooltip: {},
                xAxis: {
                    data: ["A", "B", "C", "D", "E", "F"],
                },
                yAxis: {},
                series: [{
                    name: "Sales",
                    type: "bar",
                    data: [5, 20, 36, 10, 10, 20],
                }, ],
            };

            // Menggunakan opsi yang sudah ditentukan untuk membuat chart
            myChart.setOption(option);

            var chartDom = document.getElementById("main2");
            var myChart = echarts.init(chartDom);
            var option = {
                title: {
                    text: "ECharts example",
                },
                tooltip: {},
                xAxis: {
                    data: ["A", "B", "C", "D", "E", "F"],
                },
                yAxis: {},
                series: [{
                    name: "Sales",
                    type: "bar",
                    data: [5, 20, 36, 10, 10, 20],
                }, ],
            };

            // Menggunakan opsi yang sudah ditentukan untuk membuat chart
            myChart.setOption(option);
        });
    </script>
@endsection
