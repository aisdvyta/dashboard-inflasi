document.addEventListener("DOMContentLoaded", function () {
    function renderChart(id, data, inflationValue) {
        if (!data || data.length === 0) {
            return;
        }
        let numericValue = parseFloat(
            inflationValue.textContent.replace(",", ".").trim()
        );
        var chartDom = document.getElementById(id);
        if (!chartDom) {
            return;
        }
        var myChart = echarts.init(chartDom);
        var komoditas = data.map((item) => {
            return item.nama_kom.length > 17
                ? item.nama_kom.substring(0, 17) + "\n" + item.nama_kom.substring(17)
                : item.nama_kom;
        });
        var values = data.map((item) => Number(item.andil));
        var option = {
            tooltip: {
                trigger: "axis",
                axisPointer: {
                    type: "shadow"
                }
            },
            grid: {
                left: "3%",
                right: "5%",
                bottom: "3%",
                top: "3%",
                containLabel: true,
            },
            xAxis: {
                type: "value",
                boundaryGap: [0, 0.01],
                axisLabel: {
                    color: "#063051",
                    fontSize: 12,
                    fontWeight: 'semibold',
                    formatter: function (value) {
                        return value.toFixed(2).replace('.', ',');
                    },
                },
            },
            yAxis: {
                type: "category",
                data: komoditas,
                inverse: true,
                axisLabel: {
                    color: "#063051",
                    fontSize: 12,
                    fontWeight: 'semibold',
                },
            },
            series: [
                {
                    type: "bar",
                    data: values,
                    itemStyle: {
                        color: "#4C84B0",
                    },
                    label: {
                        show: true,
                        position: "outside",
                        color: "#063051",
                        fontSize: 12,
                        fontWeight: 350,
                        formatter: function (params) {
                            return params.value.toFixed(2).replace('.', ',');
                        },
                    },
                },
            ],
        };
        myChart.setOption(option);
    }
    setTimeout(() => {
        const inflasiMtM = document.getElementById("inflasiMtM");
        const inflasiYtD = document.getElementById("inflasiYtD");
        const inflasiYoY = document.getElementById("inflasiYoY");
        renderChart("andilmtm", window.topAndilMtM, inflasiMtM);
        renderChart("andilytd", window.topAndilYtD, inflasiYtD);
        renderChart("andilyoy", window.topAndilYoY, inflasiYoY);
    }, 500);
});
