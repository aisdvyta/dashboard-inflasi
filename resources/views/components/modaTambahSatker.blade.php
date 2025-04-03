<div id="modalTambahSatker" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit">
        <h2 class="text-2xl font-[650] text-biru1 text-start mb-4">Silahkan
            <span class="text-kuning1">Tambah Satker</span> disini!</h2>
        <form onsubmit="return validateForm(event)">
            <div class="mb-4">
                <label for="namaSatker" class="block text-sm font-medium text-biru1">Kode Satker</label>
                <input type="number" id="kodeSatker" name="kodeSatker" placeholder="Masukkan kode satker"
                    class="w-96 mt-2 mb-1 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4"
                    pattern="\d{4}" maxlength="4" required>
                <p class="text-light text-xs text-biru1">Kode Satker diisikan dengan 4 angka</p>
            </div>

            <div class="mb-4">
                <label for="kodeSatker" class="block text-sm font-medium text-biru1">Nama Satker</label>
                <input type="text" id="namaSatker" name="namaSatker" placeholder="Masukkan nama satker"
                    class="w-96 mt-2 mb-1 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4">
                <p class="text-light text-xs text-biru1">Contoh: BPS Kabupaten Pacitan</p>
            </div>

            <div class="flex justify-end mt-6">
                <button
                    class="bg-biru4 font-semibold text-white px-8 py-2 rounded-lg shadow-lg mr-4 transition-all duration-200 hover:-translate-y-1"
                    onclick="closeModal()">Batal</button>
                <button
                    class="bg-kuning1 font-semibold text-biru1 px-6 py-2 rounded-lg shadow-lg transition-all duration-200 hover:-translate-y-1">
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('modalTambahSatker').classList.add('hidden');
    }

    function validateForm(event) {
        const kodeSatker = document.getElementById('kodeSatker').value;
        const kodeSatkerPattern = /^\d{4}$/; // Hanya angka dengan panjang 4 digit

        if (!kodeSatkerPattern.test(kodeSatker)) {
            alert('Kode Satker harus berupa 4 angka!');
            event.preventDefault(); // Mencegah pengiriman form jika tidak valid
            return false;
        }

        return true; // Form valid, lanjutkan pengiriman
    }
</script>
