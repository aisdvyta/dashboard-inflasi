document.addEventListener("DOMContentLoaded", function () {
    console.log("Script Loaded");

    function renderChart(id, data) {
        if (!data || data.length === 0) {
            console.warn('Data for chart is empty or undefined.');
            return;
        }

        console.log('Rendering chart', data);

        var chartDom = document.getElementById(id);
        if (!chartDom) {
            console.error('Element ${id} not found');
        return;
        }

        var myChart = echarts.init(chartDom);

        var komoditas = data.map(item =>
            item.nama_kom.length > 12 ? item.nama_kom.substring(0, 12) + "..." : item.nama_kom
        );

        // konversi nilai andil ke angka
        var values = data.map(item => Number(item.andil));

        var option = {
            tooltip: { trigger: "axis", axisPointer: { type: "shadow" } },
            grid: {
                left: "3%",
                right: "4%",
                bottom: "3%",
                top: "5%",
                containLabel: true
            },
            xAxis: { type: "value", boundaryGap: [0, 0.01] },
            yAxis: {
                type: "category",
                data: komoditas,
                inverse: true
            },
            series: [{
                type: "bar",
                data: values,
                itemStyle: {
                    color: '#4C84B0'
                }
            }],
        };

        myChart.setOption(option);
    }

    // Tunggu 1 detik untuk pastikan window.* sudah tersedia
    setTimeout(() => {
        renderChart(
            "andilmtm",
            window.topAndilMtM,
        );

        renderChart(
            "andilytd",
            window.topAndilYtD,
        );

        renderChart(
            "andilyoy",
            window.topAndilYoY,
        );
    }, 500);
});
