function renderBarchartKomoditasKotaTeratas(data, namaKota) {
    var chartDom2 = document.getElementById('barchart-komoditas-kota-teratas');
    var judul = document.getElementById('judul-barchart-kota-teratas');
    if (judul) {
        judul.textContent = 'Inflasi MtM Komoditas Utama di ' + (namaKota || '-');
    }
    if (chartDom2 && data && data.length > 0) {
        var myChart2 = echarts.init(chartDom2);
        var labels2 = data.map(item => item.nama_kom);
        var values2 = data.map(item => Number(item.inflasi_mtm));
        var option2 = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            grid: {
                left: '5%',
                right: '5%',
                bottom: '3%',
                top: 10,
                containLabel: true
            },
            xAxis: {
                type: 'value',
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: labels2,
                inverse: true,
                axisLabel: {
                    color: '#000000',
                    fontSize: 12,
                    fontWeight: 330
                },
            },
            series: [{
                type: 'bar',
                data: values2,
                itemStyle: {
                    color: '#E82D1F'
                },
                label: {
                    show: true,
                    position: 'outside',
                    color: '#063051',
                    fontSize: 12,
                    fontWeight: 350,
                    formatter: function(params) {
                        return params.value.toFixed(2).replace('.', ',');
                    },
                },
            }],
        };
        myChart2.setOption(option2);
    }
}

async function fetchInflasiKomoditasKabKota(kodeWil) {
    try {
        const params = new URLSearchParams({
            kode_wil: kodeWil,
            periode: window.periodeBarchart || '',
            jenis_data_inflasi: window.jenisDataInflasiBarchart || '',
        });
        const response = await fetch(`/dashboard/spasial/komoditas-kabkota-data?${params.toString()}`);
        if (!response.ok) throw new Error('Gagal fetch data');
        return await response.json();
    } catch (e) {
        return [];
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var inflasiKomoditasKotaTeratas = window.inflasiKomoditasKotaTeratas || [];
    var rankingKabKota = window.rankingKabKota || [];
    var currentNamaKota = rankingKabKota.length > 0 ? rankingKabKota[0].nama_wil : '';
    setTimeout(() => {
        renderBarchartKomoditasKotaTeratas(inflasiKomoditasKotaTeratas, currentNamaKota);
        document.querySelectorAll('.nama-kabkota').forEach(function(el) {
            el.addEventListener('click', async function() {
                const kodeWil = this.getAttribute('data-kode-wil');
                const namaWil = this.getAttribute('data-nama-wil');
                // Fetch data via AJAX
                const data = await fetchInflasiKomoditasKabKota(kodeWil);
                renderBarchartKomoditasKotaTeratas(data, namaWil);
            });
        });
    }, 500);
});
