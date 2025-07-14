<!-- Modal Konfirmasi Hapus Komoditas Utama -->
<div id="modalHapusKomUtama" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit relative">
        <button type="button" onclick="document.getElementById('modalHapusKomUtama').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">&times;</button>
        <h2 class="text-xl font-bold text-merah1 mb-4">Konfirmasi Hapus</h2>
        <p class="mb-6 text-biru1">Apakah Anda yakin ingin menghapus komoditas utama <span id="hapus_nama_kom" class="font-bold"></span>?</p>
        <form id="formHapusKomUtama" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalHapusKomUtama').classList.add('hidden')" class="bg-biru4 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="bg-merah1 text-white px-6 py-2 rounded-lg font-semibold hover:bg-merah2 transition">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalHapusKomUtama(kode_kom, nama_kom) {
        document.getElementById('hapus_nama_kom').textContent = nama_kom;
        document.getElementById('formHapusKomUtama').action = `/KomoditasUtama/${kode_kom}`;
        document.getElementById('modalHapusKomUtama').classList.remove('hidden');
    }
</script>
