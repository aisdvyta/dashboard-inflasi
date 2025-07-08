<div id="modalAksesDitolak" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">Akses Ditolak</h3>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-sm text-gray-600">
                Maaf, Anda tidak memiliki akses ke halaman ini. Silakan hubungi administrator untuk mendapatkan akses yang sesuai.
            </p>
        </div>

        <div class="flex justify-end space-x-3">
            <button onclick="closeModalAksesDitolak()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                Tutup
            </button>
            <a href="{{ route('login') }}" class="px-4 py-2 bg-biru4 text-white rounded-lg hover:bg-biru5 transition">
                Login Ulang
            </a>
        </div>
    </div>
</div>

<script>
function showModalAksesDitolak() {
    document.getElementById('modalAksesDitolak').classList.remove('hidden');
}

function closeModalAksesDitolak() {
    document.getElementById('modalAksesDitolak').classList.add('hidden');
}

// Tampilkan modal jika ada error akses
@if($errors->has('access'))
    showModalAksesDitolak();
@endif
</script>
