<!-- filepath: d:\Kuliah\New folder\coding\dashboard-inflasi\resources\views\components\modaEditKomoditas.blade.php -->
<div id="modalEditKomoditas" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit">
        <h2 class="text-2xl font-[650] text-biru1 text-start mb-4">Silakan
            <span class="text-kuning1">Edit Komoditas</span> di sini!
        </h2>
        <form id="formEditKomoditas" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="editKodeKomoditas" class="block text-sm font-medium text-biru1">Kode Komoditas</label>
                <input type="text" id="editKodeKomoditas" name="kode_kom" placeholder="Masukkan kode komoditas"
                    class="w-96 mt-2 mb-1 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4"
                    required>
                <p class="text-light text-xs text-biru1" id="kodeKomoditasHint">Kode harus tetap berjumlah x digit.</p>
            </div>

            <div class="mb-4">
                <label for="editNamaKomoditas" class="block text-sm font-medium text-biru1">Nama Komoditas</label>
                <input type="text" id="editNamaKomoditas" name="nama_kom" placeholder="Masukkan nama komoditas"
                    class="w-96 mt-2 mb-1 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4"
                    required>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button"
                    class="bg-biru4 font-semibold text-white px-8 py-2 rounded-lg shadow-lg mr-4 transition-all duration-200 hover:-translate-y-1"
                    onclick="closeModalEditKomoditas()">Batal</button>
                <button type="submit"
                    class="bg-kuning1 font-semibold text-biru1 px-6 py-2 rounded-lg shadow-lg transition-all duration-200 hover:-translate-y-1"
                    onclick="return validateKodeKomoditas()">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    let originalKodeLength = 0;

    function openModalEditSatker(kodeKomoditas) {
        document.getElementById('modalEditKomoditas').classList.remove('hidden');

        fetch(`/MasterKomoditas/${kodeKomoditas}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data komoditas.');
                }
                return response.json();
            })
            .then(data => {
                const kodeInput = document.getElementById('editKodeKomoditas');
                const namaInput = document.getElementById('editNamaKomoditas');
                const hint = document.getElementById('kodeKomoditasHint');

                kodeInput.value = data.kode_kom;
                namaInput.value = data.nama_kom;

                originalKodeLength = data.kode_kom.length;
                hint.innerText = `Kode harus tetap berjumlah ${originalKodeLength} digit.`;

                document.getElementById('formEditKomoditas').action = `/MasterKomoditas/${data.kode_kom}`;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengambil data komoditas.');
            });
    }

    function closeModalEditKomoditas() {
        document.getElementById('modalEditKomoditas').classList.add('hidden');
    }

    function validateKodeKomoditas() {
        const kodeInput = document.getElementById('editKodeKomoditas');
        const kode = kodeInput.value.trim();

        if (kode.length !== originalKodeLength) {
            alert(`Kode komoditas harus tetap berjumlah ${originalKodeLength} digit.`);
            return false;
        }

        return true;
    }
</script>
