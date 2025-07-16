document.addEventListener('DOMContentLoaded', function() {
    const exportPNGBtn = document.getElementById('exportPNG');
    if (exportPNGBtn) {
        exportPNGBtn.addEventListener('click', function() {
            const bulan = document.getElementById('bulan')?.value || '';
            const tahun = document.getElementById('tahun')?.value || '';
            const jenisDataInflasi = window.jenisDataInflasiBarchart || '';
            const target = document.getElementById('main-dashboard-content');
            if (!target) {
                alert('Dashboard content not found. Coba cek selector atau id elemen.');
                return;
            }
            window.exportDashboardToPNG({
                targetSelector: '#main-dashboard-content',
                filename: `dashboard-inflasi-spasial-${bulan}-${tahun}-${jenisDataInflasi}.png`,
                loadingText: 'Generating PNG...'
            });
        });
    }
    const exportExcelBtn = document.getElementById('exportExcel');
    if (exportExcelBtn) {
        exportExcelBtn.addEventListener('click', function() {
            const bulan = document.getElementById('bulan')?.value || '';
            const tahun = document.getElementById('tahun')?.value || '';
            const jenisDataInflasi = window.jenisDataInflasiBarchart || '';
            const komoditasUtama = window.komoditasUtama || '';
            const kabkota = window.kabkota || '';
            // Build query string for spasial export
            const params = new URLSearchParams({
                spasial: '1',
                bulan,
                tahun,
                jenis_data_inflasi: jenisDataInflasi,
                komoditas_utama: komoditasUtama,
                kabkota: kabkota
            });
            window.open(`/dashboard/export-excel?${params.toString()}`, '_blank');
        });
    }
    // --- Auto-select bulan terakhir jika bulan tidak tersedia di tahun baru ---
    const tahunSelect = document.getElementById('tahun');
    const bulanSelect = document.getElementById('bulan');
    if (tahunSelect && bulanSelect) {
        tahunSelect.addEventListener('change', function() {
            const tahun = tahunSelect.value;
            const bulanSekarang = bulanSelect.value;
            const bulanList = window.bulanPerTahun ? (window.bulanPerTahun[tahun] || []) : [];
            // Jika bulan sekarang tidak ada di tahun baru, set ke bulan terakhir
            if (!bulanList.includes(bulanSekarang)) {
                if (bulanList.length > 0) {
                    bulanSelect.value = bulanList[bulanList.length - 1];
                } else {
                    bulanSelect.value = '';
                }
            }
            // Submit form
            bulanSelect.form.submit();
        });
    }
});
