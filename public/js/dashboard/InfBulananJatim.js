document.addEventListener("DOMContentLoaded", function () {
    console.log("Script Loaded");

    function renderChart(id, data, title, isDeflasi = false) {
        if (!data || data.length === 0) {
            console.warn('Data for ${title} is empty or undefined.');
            return;
        }

        console.log('Rendering ${title} for ID: ${id}', data);
        console.log('isDeflasi for ${title}:', isDeflasi);

        var chartDom = document.getElementById(id);
        if (!chartDom) {
            console.error('Element ${id} not found');
        return;
        }

        var myChart = echarts.init(chartDom);

        // Jangan urutkan ulang data — biarkan urutan dari backend
        // data.sort((a, b) => isDeflasi ? a.andil - b.andil : b.andil - a.andil);

        // Potong nama komoditas maksimal 12 karakter
        var komoditas = data.map(item =>
            item.nama_kom.length > 12 ? item.nama_kom.substring(0, 12) + "..." : item.nama_kom
        );

        // Pastikan 'andil' dikonversi ke angka
        var values = data.map(item => Number(item.andil));

        var option = {
            tooltip: { trigger: "axis", axisPointer: { type: "shadow" } },
            grid: {
                left: "3%",
                right: "4%",
                bottom: "3%",
                top: "5%", // Kurangi nilai 'top' untuk membuat grafik lebih mepet ke atas
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
                    color: '#4C84B0' // Bisa diganti dinamis kalau mau beda warna untuk deflasi
                }
            }],
        };

        myChart.setOption(option);
    }

    // Tunggu 1 detik untuk pastikan window.* sudah tersedia
    setTimeout(() => {
        // Render grafik MtM
        renderChart(
            "andilmtm",
            window.topAndilMtM,
        );

        // Render grafik YtD
        renderChart(
            "andilytd",
            window.topAndilYtD,
        );

        // Render grafik YoY
        renderChart(
            "andilyoy",
            window.topAndilYoY,
        );
    }, 1000);
});
