@php
    // Pisahkan nama folder berdasarkan tanda "-"
    $parts = explode('-', $folderName);
    // Ambil kata kedua jika ada, dan ubah huruf pertama menjadi kapital
    $secondWord = isset($parts[1]) ? ucfirst($parts[1]) : '';
@endphp

<div id="modalKonfirmasiHapus{{ $id }}"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit">
        <div class="flex justify-center mb-2">
            <img src="{{ asset('images/moda/peringatanIcon.svg') }}" alt="Peringatan Icon" class="h-8 w-8">
        </div>
        <h2 class="text-2xl font-[650] text-merah1 text-center">Hapus {{ $secondWord }}</h2>
        <div class="mt-2 mb-6">
            <p class="text-biru1 mt-2 text-center text-base">Apakah Anda yakin ingin
                <span class="text-merah1">menghapus</span> {{ $secondWord }} tersebut?
            </p>
            <p class="text-biru1 text-center text-base">{{ $secondWord }} yang dihapus tidak dapat dikembalikan.</p>
        </div>
        <div class="flex justify-center mt-4">
            <button
                class="bg-kuning2 font-normal text-biru1 px-8 py-2 rounded-lg shadow-lg mr-4 transition-all duration-200 hover:-translate-y-1"
                onclick="closeModal('{{ $id }}')">
                Batal
            </button>
            <form action="{{ $formAction }}" method="POST">
                @csrf
                @method('DELETE')
                <button
                    class="bg-merah1 font-normal text-white px-8 py-2 rounded-lg shadow-lg transition-all duration-200 hover:-translate-y-1">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById('modalKonfirmasiHapus' + id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById('modalKonfirmasiHapus' + id).classList.add('hidden');
    }
</script>
