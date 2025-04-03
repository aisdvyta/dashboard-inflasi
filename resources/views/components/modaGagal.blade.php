@php
    // Pisahkan nama file berdasarkan tanda "-"
    $parts = explode('-', $fileName);
    // Ambil kata kedua jika ada, dan ubah huruf pertama menjadi kapital
    $secondWord = isset($parts[1]) ? ucfirst($parts[1]) : '';
@endphp

<div id="modalGagal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-xl shadow-lg w-fit">
        <div class="flex justify-center mb-2">
            <img src="{{ asset('images/moda/gagalIcon.svg') }}" alt="Gagal Icon" class="h-8 w-8">
        </div>
        <h2 class="text-2xl font-[650] text-biru1 text-center">Yah <span class="text-merah1">Gagal</span></h2>
        <div class="mt-2 mb-6">
            <p class="text-biru1 mt-2 text-center text-base">Anda
                <span class="text-merah1">gagal</span> menghapus {{ $secondWord }}!
            </p>
        </div>
        <div class="flex justify-center mt-4">
            <button
                class="bg-merah1 font-normal text-white px-8 py-2 rounded-lg shadow-lg transition-all duration-200 hover:-translate-y-1"
                onclick="closeModal()">Coba lagi</button>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalGagal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalGagal').classList.add('hidden');
    }
</script>
