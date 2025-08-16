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
                <span id="periodeError" class="hidden text-sm text-red-500"></span>
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

            <button type="submit" id="submitBtn"
                class="w-full bg-biru1 hover:bg-biru4 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
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

    <!-- Success Modal -->
    <div id="modalBerhasil" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="p-6 bg-white shadow-lg rounded-xl w-fit">
            <div class="flex justify-center mb-2">
                <img src="{{ asset('images/moda/berhasilIcon.svg') }}" alt="Berhasil Icon" class="w-8 h-8">
            </div>
            <h2 class="text-2xl font-[650] text-biru1 text-center">Yay <span class="text-hijau">Berhasil</span></h2>
            <div class="mt-2 mb-6">
                <p id="modalBerhasilMessage" class="mt-2 text-base text-center text-biru1">
                    Data berhasil diperbarui!
                </p>
            </div>
            <div class="flex justify-center mt-4">
                <button
                    class="px-8 py-2 font-normal text-white transition-all duration-200 rounded-lg shadow-lg bg-hijau hover:-translate-y-1"
                    onclick="closeBerhasilModal()">Oke</button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="modalGagal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="p-6 bg-white shadow-lg rounded-xl w-fit">
            <div class="flex justify-center mb-2">
                <img src="{{ asset('images/moda/gagalIcon.svg') }}" alt="Gagal Icon" class="w-8 h-8">
            </div>
            <h2 class="text-2xl font-[650] text-biru1 text-center">Yah <span class="text-merah1">Gagal</span></h2>
            <div class="mt-2 mb-6">
                <p id="modalGagalMessage" class="mt-2 text-base text-center text-biru1">
                    Terjadi kesalahan saat memperbarui data.
                </p>
            </div>
            <div class="flex justify-center mt-4">
                <button
                    class="px-8 py-2 font-normal text-white transition-all duration-200 rounded-lg shadow-lg bg-merah1 hover:-translate-y-1"
                    onclick="closeGagalModal()">Coba lagi</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openBerhasilModal(message) {
        document.getElementById('modalBerhasilMessage').textContent = message || 'Data berhasil diperbarui!';
        document.getElementById('modalBerhasil').classList.remove('hidden');
    }

    function closeBerhasilModal() {
        document.getElementById('modalBerhasil').classList.add('hidden');
    }

    function openGagalModal(message) {
        document.getElementById('modalGagalMessage').innerHTML = message || 'Terjadi kesalahan saat memperbarui data.';
        document.getElementById('modalGagal').classList.remove('hidden');
    }

    function closeGagalModal() {
        document.getElementById('modalGagal').classList.add('hidden');
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = document.getElementById('submitBtn');
        const spinnerOverlay = document.getElementById('spinnerOverlay');
        const periodeError = document.getElementById('periodeError');

        // Hide previous errors
        periodeError.classList.add('hidden');
        periodeError.textContent = '';

        // Show spinner
        submitBtn.disabled = true;
        spinnerOverlay.classList.remove('hidden');

        // Create FormData
        const formData = new FormData(form);

        // Send AJAX request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide spinner
            submitBtn.disabled = false;
            spinnerOverlay.classList.add('hidden');
            
            if (data.success) {
                // Show success modal
                openBerhasilModal(data.message);
                
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1500);
            } else {
                // Handle field-specific errors
                if (data.errors && data.errors.periode) {
                    periodeError.textContent = data.errors.periode;
                    periodeError.classList.remove('hidden');
                }
                
                // Show error modal
                let errorMsg = data.message || 'Terjadi kesalahan saat memperbarui data.';
                
                // Handle array errors (like duplicate data errors or master data errors)
                if (data.errors && Array.isArray(data.errors)) {
                    errorMsg += '<ul style="text-align:left; margin-top:10px;">';
                    data.errors.forEach(function(err) {
                        errorMsg += '<li>' + err + '</li>';
                    });
                    errorMsg += '</ul>';
                }
                
                openGagalModal(errorMsg);
            }
        })
        .catch(error => {
            // Hide spinner
            submitBtn.disabled = false;
            spinnerOverlay.classList.add('hidden');
            
            // Show generic error
            openGagalModal('Terjadi kesalahan saat memproses request. Silakan coba lagi.');
            console.error('Error:', error);
        });
    });
</script>
@endpush
