<div id="modalTambahKomUtama" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit">
        <h2 class="text-2xl font-[650] text-biru1 text-start mb-4">
            Silahkan <span class="text-kuning1">Tambah Komoditas Utama</span> di sini!
        </h2>
        <form action="{{ route('komoditas-utama.index') }}" method="POST"
            onsubmit="return validateFormTambahKomUtama(event)">
            @csrf
            <div class="mb-4">
                <label for="searchKomoditas" class="block text-sm font-medium text-biru1 mb-1">Cari Komoditas</label>
                <input type="text" id="searchKomoditas" onkeyup="filterKomoditas()"
                    placeholder="Cari kode/nama komoditas..."
                    class="w-96 mb-2 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4 text-sm" />

                <label for="komoditasUtama" class="block text-sm font-medium text-biru1">Pilih Komoditas dari Master
                    Komoditas</label>
                <select id="komoditasUtama" name="komoditas_id"
                    class="w-96 mt-2 mb-1 p-2 border border-biru5 rounded-xl focus:ring-biru4 focus:border-biru4"
                    required size="8">
                    <option value="">-- Pilih Komoditas --</option>
                    {{-- @foreach ($daftarKomoditas as $komoditas)
                        <option value="{{ $komoditas->id }}">
                            {{ $komoditas->kode_kom }} - {{ $komoditas->nama_kom }}
                        </option>
                    @endforeach --}}
                </select>
                <p class="text-light text-xs text-biru1">Pilih komoditas utama dari daftar master komoditas.</p>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModalTambahKomUtama()"
                    class="bg-biru4 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit"
                    class="bg-kuning1 text-biru1 px-6 py-2 rounded-lg font-semibold hover:bg-kuning2 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterKomoditas() {
        const input = document.getElementById('searchKomoditas');
        const filter = input.value.toLowerCase();
        const select = document.getElementById('komoditasUtama');
        const options = select.options;

        for (let i = 0; i < options.length; i++) {
            if (i === 0) continue; // skip placeholder
            const text = options[i].text.toLowerCase();
            options[i].style.display = text.includes(filter) ? '' : 'none';
        }
    }

    function closeModalTambahKomUtama() {
        document.getElementById('modalTambahKomUtama').classList.add('hidden');
    }

    function validateFormTambahKomUtama(event) {
        const komoditas = document.getElementById('komoditasUtama').value;
        if (!komoditas) {
            alert('Silakan pilih komoditas utama!');
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>
