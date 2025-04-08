<div id="modalEditSatker" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit">
        <h2 class="text-2xl font-[650] text-biru1 text-start mb-4">Silahkan
            <span class="text-kuning1">Edit Satker</span> disini!
        </h2>
        <form id="formEditSatker" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="editKodeSatker" class="block text-sm font-medium text-biru1">Kode Satker</label>
                <input type="number" id="editKodeSatker" name="kode_satker" placeholder="Masukkan kode satker"
                    class="w-96 mt-2 mb-1 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4"
                    pattern="\d{4}" maxlength="4" required readonly>
                <p class="text-light text-xs text-biru1">Kode Satker diisikan dengan 4 angka</p>
            </div>

            <div class="mb-4">
                <label for="editNamaSatker" class="block text-sm font-medium text-biru1">Nama Satker</label>
                <input type="text" id="editNamaSatker" name="nama_satker" placeholder="Masukkan nama satker"
                    class="w-96 mt-2 mb-1 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4" required>
                <p class="text-light text-xs text-biru1">Contoh: BPS Kabupaten Pacitan</p>
            </div>

            <div class="flex justify-end mt-6">
                <button
                    class="bg-biru4 font-semibold text-white px-8 py-2 rounded-lg shadow-lg mr-4 transition-all duration-200 hover:-translate-y-1"
                    onclick="closeModalEditSatker()" type="button">
                    Batal
                </button>
                <button type="submit"
                    class="bg-kuning1 font-semibold text-biru1 px-6 py-2 rounded-lg shadow-lg transition-all duration-200 hover:-translate-y-1">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModalEditSatker(kodeSatker) {
    document.getElementById('modalEditSatker').classList.remove('hidden');

    fetch(`/MasterSatker/${kodeSatker}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal mengambil data Satker.');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('editKodeSatker').value = data.kode_satker;
            document.getElementById('editNamaSatker').value = data.nama_satker;

            // Set action form untuk update
            document.getElementById('formEditSatker').action = `/MasterSatker/${data.kode_satker}`;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengambil data Satker.');
        });
}

function closeModalEditSatker() {
    document.getElementById('modalEditSatker').classList.add('hidden');
}
</script>