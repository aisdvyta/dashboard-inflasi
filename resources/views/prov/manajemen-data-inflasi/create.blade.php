@extends('layouts.dashboard')

@section('body')
    <h2 class="p-10 pl-24 text-4xl font-bold text-biru1">Silahkan
        <span class="text-kuning1">isi form</span>
        untuk menambahkan Data!
    </h2>

    <div class="max-w-lg p-6 ml-24 bg-white rounded-lg shadow-md">
        <form id="uploadForm" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="mb-4 text-left">
                <label for="periode" class="block font-semibold text-biru1">
                    Pilih Periode Data <span class="font-normal text-gray-500">(MM/YYYY)</span>
                </label>
                <input type="month" id="periode" name="periode" value="{{ old('periode') }}" required
                    class="w-full p-2 mt-1 border rounded-2xl border-biru5 focus:ring-biru5">
                <span id="periodeError" class="hidden text-sm text-red-500"></span> <!-- Error dari AJAX -->
                @error('periode')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 text-left">
                <label class="block font-semibold text-biru1">Pilih Kategori Data</label>
                <div class="flex flex-row w-64 p-4 font-normal gap-14">
                    <div class="flex flex-col gap-2">
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM 1" required
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 1</span>
                        </label>
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM 2" required
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 2</span>
                        </label>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM 3" required
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 3</span>
                        </label>
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ATAP" required
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ATAP</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-biru1">Upload Data</label>
                <input type="file" name="file" accept=".xlsx" required
                    class="w-full p-2 mt-1 border rounded-2xl border-biru5">
                <label class="block mt-1 text-xs text-biru1">Pastikan file memiliki format excel (.xlsx)</label>
            </div>

            <button type="submit" id="submitBtn"
                class="flex items-center justify-center w-full gap-2 px-4 py-2 font-semibold text-white transition duration-300 rounded-lg bg-biru1 hover:bg-biru4 disabled:opacity-50 disabled:cursor-not-allowed">
                Submit
            </button>
        </form>
    </div>

    <!-- Spinner Overlay -->
    <div id="spinnerOverlay" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-40">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 border-4 border-t-4 rounded-full border-biru1 animate-spin"
                style="border-top-color: transparent;"></div>
            <span class="mt-4 text-lg font-semibold text-white">Mengupload data...</span>
        </div>
    </div>

    <div id="modalBerhasil" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="p-6 bg-white shadow-lg rounded-xl w-fit">
            <div class="flex justify-center mb-2">
                <img src="{{ asset('images/moda/berhasilIcon.svg') }}" alt="Berhasil Icon" class="w-8 h-8">
            </div>
            <h2 class="text-2xl font-[650] text-biru1 text-center">Yay <span class="text-hijau">Berhasil</span></h2>
            <div class="mt-2 mb-6">
                <p id="modalBerhasilMessage" class="mt-2 text-base text-center text-biru1">
                    Data berhasil diupload!
                </p>
            </div>
            <div class="flex justify-center mt-4">
                <button
                    class="px-8 py-2 font-normal text-white transition-all duration-200 rounded-lg shadow-lg bg-hijau hover:-translate-y-1"
                    onclick="closeBerhasilModal()">Oke</button>
            </div>
        </div>
    </div>

    <div id="modalGagal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="p-6 bg-white shadow-lg rounded-xl w-fit">
            <div class="flex justify-center mb-2">
                <img src="{{ asset('images/moda/gagalIcon.svg') }}" alt="Gagal Icon" class="w-8 h-8">
            </div>
            <h2 class="text-2xl font-[650] text-biru1 text-center">Yah <span class="text-merah1">Gagal</span></h2>
            <div class="mt-2 mb-6">
                <p id="modalGagalMessage" class="mt-2 text-base text-center text-biru1">
                    Terjadi kesalahan saat mengupload data.
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
            document.getElementById('modalBerhasilMessage').textContent = message || 'Data berhasil diupload!';
            document.getElementById('modalBerhasil').classList.remove('hidden');
        }

        function closeBerhasilModal() {
            document.getElementById('modalBerhasil').classList.add('hidden');
        }

        function openGagalModal(message) {
            document.getElementById('modalGagalMessage').innerHTML = message || 'Terjadi kesalahan saat mengupload data.';
            document.getElementById('modalGagal').classList.remove('hidden');
        }

        function closeGagalModal() {
            document.getElementById('modalGagal').classList.add('hidden');
        }

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = document.getElementById('submitBtn');
            const spinnerOverlay = document.getElementById('spinnerOverlay');
            const periodeError = document.getElementById('periodeError');

            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('upload.inflasi.ajax') }}', true);

            xhr.onloadstart = function() {
                submitBtn.disabled = true;
                spinnerOverlay.classList.remove('hidden');
                periodeError.classList.add('hidden');
                periodeError.textContent = '';
            };

            xhr.onload = function() {
                submitBtn.disabled = false;
                spinnerOverlay.classList.add('hidden');

                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success && response.redirect_url) {
                            window.location.href = response.redirect_url + '?status=success';
                        } else {
                            openGagalModal(response.message || 'Terjadi kesalahan saat mengupload data.');
                        }
                    } catch (err) {
                        console.error('Gagal parsing respon server:', err);
                        openGagalModal('Gagal parsing respon server.');
                    }
                } else if (xhr.status === 422) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors && response.errors.periode) {
                        periodeError.textContent = response.errors.periode;
                        periodeError.classList.remove('hidden');
                    }
                    let errorMsg = 'Validasi gagal.';
                    if (response.errors && Array.isArray(response.errors)) {
                        errorMsg += '<ul style="text-align:left;">';
                        response.errors.forEach(function(err) {
                            errorMsg += '<li>' + err + '</li>';
                        });
                        errorMsg += '</ul>';
                    } else if (response.message) {
                        errorMsg += ' ' + response.message;
                    }
                    openGagalModal(errorMsg);
                } else {
                    let errorMsg = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response && response.message) {
                            errorMsg = response.message;
                        }
                    } catch (e) {}
                    openGagalModal(errorMsg);
                }
            };

            xhr.onerror = function() {
                submitBtn.disabled = false;
                spinnerOverlay.classList.add('hidden');
                openGagalModal('Terjadi kesalahan jaringan. Silakan coba lagi.');
            };

            xhr.send(formData);
        });
    </script>
@endpush
