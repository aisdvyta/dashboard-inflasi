// Format bulan-tahun ke "Jan-24" dst
function shortMonthYearLabel(bulanArr) {
    const monthMap = {
        'Januari': 'Jan', 'February': 'Feb', 'Februari': 'Feb', 'Maret': 'Mar', 'March': 'Mar',
        'April': 'Apr', 'Mei': 'Mei', 'May': 'Mei', 'Juni': 'Jun', 'June': 'Jun', 'Juli': 'Jul', 'July': 'Jul',
        'Agustus': 'Agu', 'August': 'Agu', 'September': 'Sep', 'Oktober': 'Okt', 'October': 'Okt',
        'November': 'Nov', 'Desember': 'Des', 'December': 'Des'
    };
    let tahunAktif = window.tahunCheck && window.tahunCheck.length ? window.tahunCheck : [window.tahun];
    let tahunBulanAktif = window.tahunBulan || [];
    let tahunArr = [];
    if (tahunBulanAktif.length === bulanArr.length) {
        tahunArr = tahunBulanAktif.map(tb => tb.split('-')[0].slice(-2));
    } else {
        tahunArr = Array(bulanArr.length).fill(tahunAktif[0] ? tahunAktif[0].slice(-2) : '');
    }
    return bulanArr.map((b, i) => {
        let m = monthMap[b] || b.slice(0, 3);
        let th = tahunArr[i] || '';
        return th ? `${m}-${th}` : m;
    });
}

function renderChart(domId, barData, lineData, yBarName, yLineName) {
    var chartDom = document.getElementById(domId);
    if (!chartDom || typeof echarts === 'undefined') return;
    var myChart = echarts.init(chartDom);
    var option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'cross' }
        },
        legend: { data: [yBarName, yLineName] },
        xAxis: [{
            type: 'category',
            data: shortMonthYearLabel(window.seriesData.bulan),
            axisPointer: { type: 'shadow' }
        }],
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

document.addEventListener('DOMContentLoaded', function() {
    // Render charts
    renderChart('chart-mtm', window.seriesData.andil_mtm, window.seriesData.inflasi_mtm, 'Andil MtM', 'Inflasi MtM');
    renderChart('chart-ytd', window.seriesData.andil_ytd, window.seriesData.inflasi_ytd, 'Andil YtD', 'Inflasi YtD');
    renderChart('chart-yoy', window.seriesData.andil_yoy, window.seriesData.inflasi_yoy, 'Andil YoY', 'Inflasi YoY');

    // Simpan dan restore filter tahun/bulan di localStorage GLOBAL (bukan per komoditas)
    const komoditasSelect = document.querySelector('select[name="komoditas"]');
    const tahunChecks = document.querySelectorAll('#filterFormSide .tahun-check');
    const bulanChecks = document.querySelectorAll('#filterFormSide .bulan-check');
    const formSide = document.getElementById('filterFormSide');
    let restored = false;
    const jenisDataInflasi = window.jenisDataInflasi;
    const filterKey = 'filter_global_' + jenisDataInflasi;
    if (formSide) {
        let saved = localStorage.getItem(filterKey);
        if (saved) {
            try {
                let obj = JSON.parse(saved);
                tahunChecks.forEach(cb => cb.checked = false);
                bulanChecks.forEach(cb => cb.checked = false);
                if (obj.tahun_check) {
                    obj.tahun_check.forEach(val => {
                        let cb = formSide.querySelector('input.tahun-check[value="' + val + '"]');
                        if (cb) cb.checked = true;
                    });
                }
                if (obj.tahun_bulan) {
                    obj.tahun_bulan.forEach(val => {
                        let cb = formSide.querySelector('input.bulan-check[value="' + val + '"]');
                        if (cb) cb.checked = true;
                    });
                }
                let tahunNow = Array.from(formSide.querySelectorAll('input.tahun-check:checked')).map(cb => cb.value);
                let bulanNow = Array.from(formSide.querySelectorAll('input.bulan-check:checked')).map(cb => cb.value);
                let tahunReq = window.tahunCheck || [];
                let bulanReq = window.tahunBulan || [];
                if (JSON.stringify(tahunNow) !== JSON.stringify(tahunReq) || JSON.stringify(bulanNow) !== JSON.stringify(bulanReq)) {
                    restored = true;
                    formSide.submit();
                }
            } catch (e) {}
        }
    }
    // Save on change (per jenis inflasi)
    [tahunChecks, bulanChecks, komoditasSelect].forEach(list => {
        if (!list) return;
        (list.length ? list : [list]).forEach(el => {
            el.addEventListener('change', function() {
                let tahunVals = Array.from(formSide.querySelectorAll('input.tahun-check:checked')).map(cb => cb.value);
                let bulanVals = Array.from(formSide.querySelectorAll('input.bulan-check:checked')).map(cb => cb.value);
                localStorage.setItem(filterKey, JSON.stringify({
                    tahun_check: tahunVals,
                    tahun_bulan: bulanVals
                }));
            });
        });
    });

    // Reset filter tahun/bulan & komoditas ke default saat ganti jenis inflasi
    document.querySelectorAll('.tab-link').forEach(function(tab) {
        tab.addEventListener('click', function(e) {
            const jenis = this.getAttribute('data-tab');
            localStorage.removeItem('filter_global_' + jenis);
            e.preventDefault();
            let url = new URL(this.href, window.location.origin);
            url.searchParams.delete('tahun_bulan');
            url.searchParams.delete('tahun_check');
            url.searchParams.delete('komoditas');
            window.location.href = url.toString();
        });
    });

    // Export PNG
    const exportPngBtn = document.getElementById('exportPNG');
    if (exportPngBtn) {
        exportPngBtn.addEventListener('click', function() {
            const komoditas = document.querySelector('select[name="komoditas"]')?.value || '';
            const tahun = window.tahun || '';
            const jenisDataInflasi = window.jenisDataInflasi || '';
            const target = document.getElementById('main-dashboard-content');
            if (!target) {
                alert('Dashboard content not found. Coba cek selector atau id elemen.');
                return;
            }
            window.exportDashboardToPNG({
                targetSelector: '#main-dashboard-content',
                filename: `dashboard-inflasi-series-${komoditas}-${tahun}-${jenisDataInflasi}.png`,
                loadingText: 'Generating PNG...'
            });
        });
    }

    // Export Excel
    const exportExcelBtn = document.getElementById('exportExcelSeries');
    if (exportExcelBtn) {
        exportExcelBtn.addEventListener('click', function() {
            const jenisDataInflasi = window.jenisDataInflasi || '';
            const komoditas = window.komoditas || '';
            const tahun = window.tahun || '';
            const tahunCheck = window.tahunCheck || [];
            const tahunBulan = window.tahunBulan || [];
            const params = new URLSearchParams({
                series: '1',
                jenis_data_inflasi: jenisDataInflasi,
                komoditas: komoditas,
                tahun: tahun
            });
            tahunCheck.forEach(val => params.append('tahun_check[]', val));
            tahunBulan.forEach(val => params.append('tahun_bulan[]', val));
            window.open(`/dashboard/export-excel?${params.toString()}`, '_blank');
        });
    }

    // Tampilkan/hilangkan bulan sesuai tahun yang dichecklist dan auto-submit form jika ada perubahan
    const tahunChecks2 = document.querySelectorAll('#filterFormSide .tahun-check');
    const bulanChecks2 = document.querySelectorAll('#filterFormSide .bulan-check');
    const form2 = document.getElementById('filterFormSide');
    tahunChecks2.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            document.querySelectorAll('#filterFormSide .bulan-group').forEach(function(bg) {
                const tahun = bg.getAttribute('data-tahun');
                if (document.querySelector('#filterFormSide input.tahun-check[value="' + tahun + '"]:checked')) {
                    bg.style.display = 'block';
                } else {
                    bg.style.display = 'none';
                }
            });
            form2.submit();
        });
    });
    bulanChecks2.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            form2.submit();
        });
    });
});
