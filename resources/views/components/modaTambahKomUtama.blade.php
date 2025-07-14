<!-- Modal Tambah Komoditas Utama -->
<div id="modalTambahKomUtama" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit relative">
        <button type="button" onclick="document.getElementById('modalTambahKomUtama').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">&times;</button>
        <h2 class="text-2xl font-[650] text-biru1 text-start mb-4">
            Silahkan <span class="text-kuning1">Tambah Komoditas Utama</span> di sini!
        </h2>
        <form action="{{ $formAction ?? route('komoditas-utama.storeKomUtama') }}" method="POST" onsubmit="return validateFormTambahKomUtama(event)">
            @csrf
            <div class="mb-4">
                <label for="searchKomoditas" class="block text-sm font-medium text-biru1 mb-1">Cari Komoditas</label>
                <input type="text" id="searchKomoditas" placeholder="Cari kode/nama komoditas..." class="w-96 mb-2 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4 text-sm" />
                <div id="radioKomoditasList" class="max-h-48 overflow-y-auto border border-biru5 rounded-xl p-2 bg-gray-50">
                    <!-- Daftar komoditas dengan radio button akan muncul di sini -->
                </div>
                <p class="text-light text-xs text-biru1 mt-1">Pilih satu komoditas utama dari hasil pencarian.</p>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('modalTambahKomUtama').classList.add('hidden')" class="bg-biru4 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="bg-kuning1 text-biru1 px-6 py-2 rounded-lg font-semibold hover:bg-kuning2 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchKomoditas');
    const radioList = document.getElementById('radioKomoditasList');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (query.length < 2) {
            radioList.innerHTML = '';
            return;
        }
        fetch(`{{ route('komoditas-utama.searchKomUtama') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                radioList.innerHTML = '';
                if (data.length === 0) {
                    radioList.innerHTML = '<div class="text-gray-400 text-sm">Tidak ada hasil.</div>';
                    return;
                }
                data.forEach(item => {
                    const label = document.createElement('label');
                    label.className = 'flex items-center gap-2 py-1 cursor-pointer hover:bg-biru4/10 rounded px-2';
                    label.innerHTML = `
                        <input type="radio" name="komoditas" value="${item.kode_kom}" class="komoditas-radio">
                        <span class="font-semibold">${item.nama_kom}</span>
                        <span class="text-xs text-gray-500">(${item.kode_kom})</span>
                    `;
                    radioList.appendChild(label);
                });
            });
    });

    function validateFormTambahKomUtama(event) {
        const checked = radioList.querySelector('input.komoditas-radio:checked');
        if (!checked) {
            alert('Silakan pilih satu komoditas utama!');
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
