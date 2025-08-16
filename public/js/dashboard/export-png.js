// public/js/dashboard/export-png.js
// Fungsi utama export PNG dashboard
window.exportDashboardToPNG = async function({targetSelector, filename = 'dashboard.png', loadingText = 'Generating PNG...'}) {
    // Overlay loading
    const loading = document.createElement('div');
    loading.style.position = 'fixed';
    loading.style.top = '0';
    loading.style.left = '0';
    loading.style.width = '100vw';
    loading.style.height = '100vh';
    loading.style.display = 'flex';
    loading.style.alignItems = 'center';
    loading.style.justifyContent = 'center';
    loading.style.backgroundColor = 'rgba(0,0,0,0.7)';
    loading.style.color = 'white';
    loading.style.fontSize = '1.5rem';
    loading.style.zIndex = '9999';
    loading.textContent = loadingText;
    document.body.appendChild(loading);
    try {
        // Inject style global agar angka rapi di screenshot
        const style = document.createElement('style');
        style.innerHTML = `
            .angka-screenshot {
                line-height: 1.2 !important;
                vertical-align: middle !important;
                padding: 0 !important;
                margin: 0 !important;
                display: inline-block !important;
            }
        `;
        document.head.appendChild(style);
        // Pastikan font sudah termuat
        if (document.fonts) await document.fonts.ready;
        const content = document.querySelector(targetSelector);
        if (!content) throw new Error('Dashboard content not found');
        await new Promise(resolve => setTimeout(resolve, 500)); // biar render stabil
        const rect = content.getBoundingClientRect();
        const canvas = await html2canvas(content, {
            useCORS: true,
            scale: 2,
            width: rect.width,
            height: rect.height,
            scrollX: 0,
            scrollY: 0
        });
        document.head.removeChild(style);
        const image = canvas.toDataURL('image/png');
        const a = document.createElement('a');
        a.href = image;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    } catch (error) {
        console.error('Error generating PNG:', error);
        alert('Terjadi kesalahan saat menghasilkan PNG. Silakan coba lagi.');
    } finally {
        document.body.removeChild(loading);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // const exportPngBtn = document.getElementById('exportPNG');
    // if (exportPngBtn) {
    //     exportPngBtn.addEventListener('click', function() {
    //         // kode export PNG, misal:
    //         html2canvas(document.getElementById('main-dashboard-content')).then(function(canvas) {
    //             var link = document.createElement('a');
    //             link.download = 'dashboard-series.png';
    //             link.href = canvas.toDataURL();
    //             link.click();
    //         });
    //     });
    // }

    const exportExcelBtn = document.getElementById('exportExcelSeries');
    if (exportExcelBtn) {
        exportExcelBtn.addEventListener('click', function() {
            // Build the correct export URL with all relevant parameters
            let url = '/dashboard/export-excel?series=1'
                + '&komoditas=' + encodeURIComponent(window.komoditas)
                + '&jenis_data_inflasi=' + encodeURIComponent(window.jenisDataInflasi)
                + '&tahun=' + encodeURIComponent(window.tahun);
            // Add tahun_check[]
            if (Array.isArray(window.tahunCheck)) {
                window.tahunCheck.forEach(function(val) {
                    url += '&tahun_check[]=' + encodeURIComponent(val);
                });
            }
            // Add tahun_bulan[]
            if (Array.isArray(window.tahunBulan)) {
                window.tahunBulan.forEach(function(val) {
                    url += '&tahun_bulan[]=' + encodeURIComponent(val);
                });
            }
            window.location.href = url;
        });
    }
});
