<!-- Modal Edit Komoditas Utama -->
<div id="modalEditKomUtama" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit relative">
        <button type="button" onclick="document.getElementById('modalEditKomUtama').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">&times;</button>
        <h2 class="text-2xl font-[650] text-biru1 text-start mb-4">
            Edit <span class="text-kuning1">Komoditas Utama</span>
        </h2>
        <form id="formEditKomUtama" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_search_komoditas" class="block text-sm font-medium text-biru1 mb-1">Cari Komoditas</label>
                <input type="text" id="edit_search_komoditas" placeholder="Cari kode/nama komoditas..." class="w-96 mb-2 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4 text-sm" />
                <div id="edit_radioKomoditasList" class="max-h-48 overflow-y-auto border border-biru5 rounded-xl p-2 bg-gray-50">
                    <!-- Daftar komoditas dengan radio button akan muncul di sini -->
                </div>
                <input type="hidden" id="edit_kode_kom" name="kode_kom" />
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('modalEditKomUtama').classList.add('hidden')" class="bg-biru4 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="bg-kuning1 text-biru1 px-6 py-2 rounded-lg font-semibold hover:bg-kuning2 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let kodeKomUtamaLama = null;

    function openModalEditKomUtama(kode_kom) {
        // Simpan kode lama untuk update
        kodeKomUtamaLama = kode_kom;
        // Kosongkan input dan hasil
        document.getElementById('edit_search_komoditas').value = '';
        document.getElementById('edit_radioKomoditasList').innerHTML = '';
        // Set action form
        document.getElementById('formEditKomUtama').action = `/KomoditasUtama/${kode_kom}`;
        document.getElementById('modalEditKomUtama').classList.remove('hidden');
    }

    const editSearchInput = document.getElementById('edit_search_komoditas');
    const editRadioList = document.getElementById('edit_radioKomoditasList');
    const editKodeKomInput = document.getElementById('edit_kode_kom');

    editSearchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            editRadioList.innerHTML = '';
            return;
        }
        fetch(`{{ route('komoditas-utama.searchKomUtama') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                editRadioList.innerHTML = '';
                if (data.length === 0) {
                    editRadioList.innerHTML = '<div class="text-gray-400 text-sm">Tidak ada hasil.</div>';
                    return;
                }
                data.forEach(item => {
                    const label = document.createElement('label');
                    label.className = 'flex items-center gap-2 py-1 cursor-pointer hover:bg-biru4/10 rounded px-2';
                    label.innerHTML = `
                        <input type="radio" name="edit_komoditas" value="${item.kode_kom}" class="edit-komoditas-radio">
                        <span class="font-semibold">${item.nama_kom}</span>
                        <span class="text-xs text-gray-500">(${item.kode_kom})</span>
                    `;
                    label.querySelector('input').addEventListener('change', function() {
                        if (this.checked) {
                            editKodeKomInput.value = item.kode_kom;
                        }
                    });
                    editRadioList.appendChild(label);
                });
            });
    });

    document.getElementById('formEditKomUtama').addEventListener('submit', function(e) {
        if (!editKodeKomInput.value) {
            alert('Silakan pilih satu komoditas utama!');
            e.preventDefault();
            return false;
        }
    });
</script>
