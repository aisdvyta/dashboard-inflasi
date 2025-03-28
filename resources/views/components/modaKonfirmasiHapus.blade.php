<div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-80 text-center">
        <div class="flex justify-center">
            <img src="{{ asset('images/warning.svg') }}" class="w-12 h-12" alt="Warning Icon">
        </div>
        <h2 class="text-xl font-bold text-gray-800 mt-2">Hapus Data</h2>
        <p class="text-gray-600 mt-2">Anda yakin <span class="text-red-600 font-semibold">menghapus</span> Data tersebut?
        </p>
        <div class="mt-4 flex justify-center gap-4">
            <button onclick="closeModal()"
                class="bg-yellow-200 text-gray-800 px-4 py-2 rounded-lg shadow-md">Cancel</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md">Hapus</button>
            </form>
        </div>
    </div>
</div>
