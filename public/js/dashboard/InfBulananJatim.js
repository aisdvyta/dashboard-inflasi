document.addEventListener("DOMContentLoaded", function () {
    console.log("Script Loaded");

    function renderChart(id, data, inflationValue) {
        if (!data || data.length === 0) {
            console.warn("Data for chart is empty or undefined.");
            return;
        }

        // Extract numeric value from the element's text content
        let numericValue = parseFloat(
            inflationValue.textContent.replace(",", ".").trim()
        );
        console.log("Rendering chart with inflation value:", numericValue);

        var chartDom = document.getElementById(id);
        if (!chartDom) {
            console.error("Element ${id} not found");
            return;
        }

        var myChart = echarts.init(chartDom);

        var komoditas = data.map((item) => {
            return item.nama_kom.length > 17
                ? item.nama_kom.substring(0, 17) +
                      "\n" +
                      item.nama_kom.substring(17)
                : item.nama_kom;
        });

        // konversi nilai andil ke angka
        var values = data.map((item) => Number(item.andil));

        var option = {
            tooltip: { trigger: "axis", axisPointer: { type: "shadow" } },
            grid: {
                left: "5%",
                right: "5%",
                bottom: "3%",
                top: "3%",
                containLabel: true,
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
                        align: numericValue < 0 ? "left" : "right",
                        color: "#063051",
                        fontSize: 12,
                        fontWeight: 350,
                        distance: numericValue < 0 ? 35 : 0,
                        formatter: function (params) {
                            return params.value.toFixed(2);
                        },
                    },
                },
            ],
        };

        myChart.setOption(option);
    }

    // Tunggu 0.5 detik untuk pastikan window.* sudah tersedia
    setTimeout(() => {
        // Get the elements containing inflation values
        const inflasiMtM = document.getElementById("inflasiMtM");
        const inflasiYtD = document.getElementById("inflasiYtD");
        const inflasiYoY = document.getElementById("inflasiYoY");

        renderChart("andilmtm", window.topAndilMtM, inflasiMtM);
        renderChart("andilytd", window.topAndilYtD, inflasiYtD);
        renderChart("andilyoy", window.topAndilYoY, inflasiYoY);
    }, 500);
});
