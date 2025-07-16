document.addEventListener('DOMContentLoaded', function() {
    // Ambil data dari window (sudah di-push dari blade)
    let kelompokData = (window.tabelKelompok || []).filter(function (row) {
        return String(row.nama_kom).trim().toLowerCase() !== 'umum';
    });

    // Urutkan dari terendah ke tertinggi berdasarkan andil_mtm (atau andil_MtM)
    kelompokData = kelompokData.slice().sort((a, b) => {
        const aVal = parseFloat(a.andil_mtm ?? a.andil_MtM ?? 0) || 0;
        const bVal = parseFloat(b.andil_mtm ?? b.andil_MtM ?? 0) || 0;
        return aVal - bVal;
    });

    // Label: nama kelompok, Data: andil_mtm (atau andil_MtM jika key beda, pastikan number)
    const labels = kelompokData.map(item => item.nama_kom);
    const values = kelompokData.map(item => parseFloat(item.andil_mtm ?? item.andil_MtM ?? 0) || 0);

    const chartDom = document.getElementById('andilKelompokBar');
    if (chartDom && typeof echarts !== 'undefined') {
        const myChart = echarts.init(chartDom);

        // Ambil juga nilai inflasi untuk tooltip
        const inflasiValues = kelompokData.map(item => parseFloat(item.inflasi_mtm ?? item.inflasi_MtM ?? 0) || 0);

        // Fungsi untuk memecah label jadi dua baris jika terlalu panjang
        function wrapLabel(label, maxLen = 20) {
            if (label.length <= maxLen) return label;
            // Pecah di spasi terdekat sebelum maxLen
            let idx = label.lastIndexOf(' ', maxLen);
            if (idx === -1) idx = maxLen;
            return label.slice(0, idx) + '\n' + label.slice(idx + 1);
        }

        const option = {
            grid: {
                left: 130,
                right: 20,
                top: 10,
                bottom: 20
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow',
                    z: 100, // pointer di atas elemen lain
                    label: {
                        show: false
                    }
                },
                formatter: function(params) {
                    const p = Array.isArray(params) ? params[0] : params;
                    const idx = p.dataIndex;
                    const andil = p.value.toFixed(2).replace('.', ',');
                    const inflasi = (inflasiValues[idx] ?? 0).toFixed(2).replace('.', ',');
                    return `<b style="color:#063051;font-size:14px;">${labels[idx]}</b><br/>
                        <span style="font-size:14px;"> Andil Inflasi: ${andil}<br/>
                        <span style="font-size:14px;"> Inflasi: ${inflasi}`;
                }
            },
            xAxis: {
                type: 'value',
                axisLabel: {
                    fontWeight: 'semibold',
                    color: '#3B4A6B',
                    formatter: function(value) {
                        return value.toFixed(2).replace('.', ',');
                    }
                },
                splitLine: {
                    show: false,
                }
            },
            yAxis: {
                type: 'category',
                data: labels.map(l => wrapLabel(l)),
                axisLabel: {
                    fontWeight: 'semibold',
                    color: '#063051',
                    fontSize: 18,
                    lineHeight: 12
                },
                axisTick: {
                    show: false
                },
                // Tambahkan ini agar area hover seluruh baris y aktif
                triggerEvent: true
            },
            series: [{
                type: 'bar',
                data: values,
                label: {
                    show: true,
                    position: 'right',
                    fontWeight: 'semibold',
                    fontSize: 12,
                    color: '#063051',
                    formatter: function(params) {
                        // Format angka dengan koma
                        return params.value.toFixed(2).replace('.', ',');
                    }
                },
                itemStyle: {
                    color: '#4C84B0'
                },
                barWidth: 24,
                emphasis: {
                    focus: 'series'
                }
            }]
        };
        myChart.setOption(option);
        window.addEventListener('resize', () => myChart.resize());
    }

    // Export PNG
    const exportPngBtn = document.getElementById('exportPNG');
    if (exportPngBtn) {
        exportPngBtn.addEventListener('click', function() {
            const bulan = window.bulan || '';
            const tahun = window.tahun || '';
            const jenisDataInflasi = window.jenisDataInflasi || '';
            const target = document.getElementById('main-dashboard-content');
            if (!target) {
                alert('Dashboard content not found. Coba cek selector atau id elemen.');
                return;
            }
            window.exportDashboardToPNG({
                targetSelector: '#main-dashboard-content',
                filename: `dashboard-inflasi-kelompok-${bulan}-${tahun}-${jenisDataInflasi}.png`,
                loadingText: 'Generating PNG...'
            });
        });
    }

    // Export Excel
    const exportExcelBtn = document.getElementById('exportExcelKelompok');
    if (exportExcelBtn) {
        exportExcelBtn.addEventListener('click', function() {
            const bulan = window.bulan || '';
            const tahun = window.tahun || '';
            const jenisDataInflasi = window.jenisDataInflasi || '';
            const kabkota = window.kabkota || '';
            const params = new URLSearchParams({
                kelompok: '1',
                bulan,
                tahun,
                jenis_data_inflasi: jenisDataInflasi,
                kabkota: kabkota
            });
            window.open(`/dashboard/export-excel?${params.toString()}`, '_blank');
        });
    }
}); 