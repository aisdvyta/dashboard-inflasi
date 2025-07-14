@extends('layouts.dashboard')

@section('body')
    <h2 class="text-4xl font-bold text-biru1 p-10 pl-24">Silahkan
        <span class="text-kuning1">edit form</span>
        untuk memperbarui Data!
    </h2>

    <div class="max-w-lg bg-white shadow-md rounded-lg p-6 ml-24">
        <form action="{{ route('manajemen-data-inflasi.update', $upload->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4" id="editForm">
            @csrf
            @method('PUT')

            <div class="mb-4 text-left">
                <label for="periode" class="block text-biru1 font-semibold">
                    Pilih Periode Data <span class="text-gray-500 font-normal">(MM/YYYY)</span>
                </label>
                <input type="month" id="periode" name="periode"
                    value="{{ \Carbon\Carbon::parse($upload->periode)->format('Y-m') }}" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5">
            </div>

            <div class="mb-4 text-left">
                <label class="block text-biru1 font-semibold">Pilih Kategori Data</label>
                <div class="flex flex-row w-64 gap-14 p-4 font-normal">
                    <div class="flex flex-col gap-2">
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM 1"
                                {{ $upload->jenis_data_inflasi == 'ASEM 1' ? 'checked' : '' }}
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 1</span>
                        </label>
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM 2"
                                {{ $upload->jenis_data_inflasi == 'ASEM 2' ? 'checked' : '' }}
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 2</span>
                        </label>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM 3"
                                {{ $upload->jenis_data_inflasi == 'ASEM 3' ? 'checked' : '' }}
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 3</span>
                        </label>
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ATAP"
                                {{ $upload->jenis_data_inflasi == 'ATAP' ? 'checked' : '' }}
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ATAP</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-biru1 font-medium mb-1">Upload Data Baru (Opsional)</label>
                <input type="file" name="file" accept=".xlsx"
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5">
                <label class="block text-xs text-biru1 font-light mb-1">Kosongkan jika tidak ingin mengganti file.</label>
            </div>

            <button type="submit"
                class="w-full bg-biru1 hover:bg-biru4 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                Update
            </button>
        </form>
    </div>

    <!-- Spinner Overlay -->
    <div id="spinnerOverlay" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-40">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 border-4 border-t-4 rounded-full border-biru1 animate-spin"
                style="border-top-color: transparent;"></div>
            <span class="mt-4 text-lg font-semibold text-white">Memperbarui data...</span>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('editForm').addEventListener('submit', function(e) {
        document.getElementById('spinnerOverlay').classList.remove('hidden');
    });
</script>
@endpush
