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

        var komoditas = data.map(item => {
            return item.nama_kom.length > 17
                ? item.nama_kom.substring(0, 17) + "\n" + item.nama_kom.substring(17)
                : item.nama_kom;
        });

        // konversi nilai andil ke angka
        var values = data.map(item => Number(item.andil));

        var option = {
            tooltip: { trigger: "axis", axisPointer: { type: "shadow" } },
            grid: {
                left: "2%",
                right: "2%",
                bottom: "3%",
                top: "3%",
                containLabel: true
            },
            xAxis: { type: "value", boundaryGap: [0, 0.01] },
            yAxis: {
                type: "category",
                data: komoditas,
                inverse: true,
                axisLabel: {
                    color: "#000000",
                    fontSize: 12,
                    fontWeight: 330,
                },
            },
            series: [{
                type: "bar",
                data: values,
                itemStyle: {
                    color: '#4C84B0'
                },
                label: {
                    show: true,
                    position: 'right',
                    formatter: '{c}',
                    color: '#4C84B0',
                    fontSize: 12,
                    fontWeight: 350,
                },
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
