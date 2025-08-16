document.addEventListener('DOMContentLoaded', function() {
    // Komoditas
    const searchKomoditas = document.getElementById('search-komoditas');
    const checklistKomoditas = document.getElementById('checklist-komoditas');
    if (searchKomoditas && checklistKomoditas) {
        searchKomoditas.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            checklistKomoditas.querySelectorAll('label').forEach(function(label) {
                const text = label.textContent.toLowerCase();
                label.style.display = text.includes(val) ? '' : 'none';
            });
        });
    }
    // Wilayah
    const searchWilayah = document.getElementById('search-wilayah');
    const checklistWilayah = document.getElementById('checklist-wilayah');
    if (searchWilayah && checklistWilayah) {
        searchWilayah.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            checklistWilayah.querySelectorAll('label').forEach(function(label) {
                const text = label.textContent.toLowerCase();
                label.style.display = text.includes(val) ? '' : 'none';
            });
        });
    }
    // Setelah event searchWilayah
    if (checklistWilayah) {
        const checkWilayahSemua = document.getElementById('check-wilayah-semua');
        if (checkWilayahSemua) {
            checkWilayahSemua.addEventListener('change', function() {
                const allCheckboxes = checklistWilayah.querySelectorAll('input[type="checkbox"]');
                allCheckboxes.forEach(cb => {
                    if (cb.style.display !== 'none') {
                        cb.checked = this.checked;
                    }
                });
            });
            // Jika ada satu yang di-uncheck, uncheck "Pilih Semua"
            checklistWilayah.addEventListener('change', function(e) {
                if (e.target.type === 'checkbox') {
                    const all = checklistWilayah.querySelectorAll('input[type="checkbox"]');
                    const checked = checklistWilayah.querySelectorAll('input[type="checkbox"]:checked');
                    checkWilayahSemua.checked = all.length === checked.length;
                }
            });
        }
    }
    // TAMPILKAN TABEL DINAMIS
    const btnTampilkan = document.getElementById('btn-tampilkan-tabel');
    const hasilDiv = document.getElementById('tabel-dinamis-hasil');
    const form = document.getElementById('form-tabel-dinamis');
    // Ambil mapping kode_wil -> nama_wil dari window (harus di-set di blade)
    const wilayahMap = window.wilayahMap || {};
    function getCheckedValues(name) {
        if (!form) return [];
        return Array.from(form.querySelectorAll(`[name='${name}[]']:checked`)).map(cb => cb.value);
    }
    function getCheckedRadio(name) {
        if (!form) return null;
        const radio = form.querySelector(`[name='${name}']:checked`);
        return radio ? radio.value : null;
    }
    function showAlert(msg) {
        if (hasilDiv) {
            hasilDiv.innerHTML = `<div class='bg-red-100 text-red-700 px-4 py-2 rounded mb-4'>${msg}</div>`;
        }
    }
    if (btnTampilkan && hasilDiv && form) {
        btnTampilkan.addEventListener('click', function() {
            const komoditas = getCheckedValues('komoditas');
            const wilayah = getCheckedValues('wilayah');
            const periode = getCheckedRadio('periode');
            const value = getCheckedRadio('value');
            // Validasi
            if (komoditas.length === 0) {
                showAlert('Pilih minimal satu komoditas!');
                return;
            }
            if (wilayah.length === 0) {
                showAlert('Pilih minimal satu wilayah!');
                return;
            }
            if (!periode) {
                showAlert('Pilih tepat satu periode!');
                return;
            }
            if (!value) {
                showAlert('Pilih salah satu jenis data!');
                return;
            }
            // Render tabel dinamis (loading)
            let html = `<div class='text-center text-biru1 font-semibold mb-2'>Memuat data...</div>`;
            hasilDiv.innerHTML = html;
            // Fetch data AJAX
            fetch('/dashboard/spasial/tabel-dinamis-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
                },
                body: JSON.stringify({
                    komoditas: komoditas,
                    wilayah: wilayah,
                    periode: periode,
                    value: value
                })
            })
            .then(res => res.json())
            .then(data => {
                // Simpan data terakhir ke window
                window.lastTabelDinamisData = {
                    komoditas: komoditas,
                    wilayah: wilayah,
                    periode: periode,
                    value: value,
                    data: data
                };
                // Build table: header = wilayah, baris = komoditas
                let table = `<div class='overflow-x-auto'><table class='min-w-full border border-gray-300 rounded-lg bg-white tabel-dinamis-mini text-xs'>`;
                // Header
                table += `<thead><tr><th class='border px-1 py-1 bg-biru1 text-white whitespace-nowrap'>Komoditas</th>`;
                wilayah.forEach(w => {
                    table += `<th class='border px-1 py-1 bg-biru1 text-white whitespace-nowrap'>${wilayahMap[w] || w}</th>`;
                });
                table += `</tr></thead>`;
                // Body
                table += `<tbody>`;
                komoditas.forEach(k => {
                    table += `<tr>`;
                    table += `<td class='border px-1 py-1 font-semibold whitespace-nowrap'>${k}</td>`;
                    wilayah.forEach(w => {
                        let val = (data[w] && data[w][k] != null) ? Number(data[w][k]).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-';
                        table += `<td class='border px-1 py-1 text-center whitespace-nowrap'>${val}</td>`;
                    });
                    table += `</tr>`;
                });
                table += `</tbody></table></div>`;
                hasilDiv.innerHTML = table;
            })
            .catch(() => {
                hasilDiv.innerHTML = `<div class='bg-red-100 text-red-700 px-4 py-2 rounded mb-4'>Gagal memuat data!</div>`;
            });
        });
    }
    // EXPORT EXCEL TABEL DINAMIS
    const btnExportExcel = document.getElementById('exportExcelTabelDinamis');
    if (btnExportExcel && form) {
        btnExportExcel.addEventListener('click', function(e) {
            e.preventDefault();
            // Cek apakah sudah tampilkan
            if (!window.lastTabelDinamisData) {
                showAlert('Silakan tekan tombol Tampilkan terlebih dahulu!');
                return;
            }
            const { komoditas, wilayah, periode, value } = window.lastTabelDinamisData;
            // Kirim AJAX POST ke backend untuk export
            fetch('/dashboard/export-tabel-dinamis', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
                },
                body: JSON.stringify({
                    komoditas: komoditas,
                    wilayah: wilayah,
                    periode: periode,
                    value: value
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal export file');
                return response.blob();
            })
            .then(blob => {
                // Download file
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'Tabel-Dinamis.xlsx';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            })
            .catch(() => {
                showAlert('Gagal export file!');
            });
        });
    }
});
