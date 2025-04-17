@extends('layouts.dashboard')

@section('body')
    <h2 class="text-4xl font-bold text-biru1 p-10 pl-24">Silahkan
        <span class="text-kuning1">isi form</span>
        untuk menambahkan Data!
    </h2>

    <div class="max-w-lg bg-white shadow-md rounded-lg p-6 ml-24">
        <form id="uploadForm" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="mb-4 text-left">
                <label for="nama" class="block text-biru1 font-semibold">Username Upload</label>
                <input type="text" id="nama" name="nama" value="{{ Auth::user()->nama }}" readonly
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 bg-gray-100 focus:ring-biru5">
            </div>

            <div class="mb-4 text-left">
                <label for="periode" class="block text-biru1 font-semibold">
                    Pilih Periode Data <span class="text-gray-500 font-normal">(MM/YYYY)</span>
                </label>
                <input type="month" id="periode" name="periode" value="{{ old('periode') }}" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5">
                <span id="periodeError" class="text-red-500 text-sm hidden"></span> <!-- Error dari AJAX -->
                @error('periode')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 text-left">
                <label class="block text-biru1 font-semibold">Pilih Kategori Data</label>
                <div class="flex flex-row w-64 gap-14 p-4 font-normal">
                    <div class="flex flex-col gap-2">
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM1" required
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 1</span>
                        </label>
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM2" required
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 2</span>
                        </label>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM3" required
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
                <label class="block text-biru1 font-medium mb-1">Upload Data</label>
                <input type="file" id="fileInput" name="file" accept=".xlsx" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5">
                <label class="block text-xs text-biru1 mt-1">Pastikan file memiliki format excel (.xlsx)</label>
            </div>

            <!-- Progress bar -->
            <div id="progressContainer" class="hidden w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div id="progressBar"
                    class="bg-biru1 h-4 text-xs text-white text-center leading-4 transition-all duration-300 ease-in-out"
                    style="width: 0%">0%</div>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full bg-biru1 hover:bg-biru4 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                Submit
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = document.getElementById('submitBtn');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const periodeError = document.getElementById('periodeError'); // Elemen untuk menampilkan error periode

            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('upload.inflasi.ajax') }}', true);

            let manualProgressInterval;

            xhr.onloadstart = function() {
                submitBtn.disabled = true;
                progressContainer.classList.remove('hidden');
                progressBar.style.width = '0%';
                progressBar.textContent = '0%';
                progressBar.style.backgroundColor = '#4C84B0';
                periodeError.classList.add('hidden');
                periodeError.textContent = '';
            };

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 50); // max sampai 80% untuk upload
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent + '%';
                }
            });

            xhr.upload.onloadend = function() {
                // Setelah upload selesai, lanjut animasi manual progress dari 80 ke 99
                let current = parseInt(progressBar.style.width) || 50;
                manualProgressInterval = setInterval(() => {
                    if (current < 99) {
                        current += 1;
                        progressBar.style.width = current + '%';
                        progressBar.textContent = current + '%';
                    } else {
                        clearInterval(manualProgressInterval);
                    }
                }, 100); // kecepatan naiknya (semakin kecil = makin cepat)
            };

            xhr.onload = function() {
                clearInterval(manualProgressInterval);
                submitBtn.disabled = false;

                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            progressBar.style.width = '100%';
                            progressBar.textContent = '100%';
                            progressBar.style.backgroundColor = '#4CAF50'; // Hijau untuk sukses
                            window.location.href = response.redirect_url; // Langsung ke index
                        } else {
                            progressBar.style.backgroundColor = '#F15A42';
                            progressBar.textContent = 'Gagal';
                            console.error('Upload gagal:', response.message || 'Unknown error');
                        }
                    } catch (err) {
                        progressBar.style.backgroundColor = '#F15A42';
                        progressBar.textContent = 'Gagal';
                        console.error('Gagal parsing respon server:', err);
                    }
                } else if (xhr.status === 422) {
                    const response = JSON.parse(xhr.responseText);
                    progressBar.style.backgroundColor = '#F15A42';
                    progressBar.textContent = 'Validasi Gagal';

                    // Tampilkan pesan error di console
                    if (response.message) {
                        console.error('Validasi gagal:', response.message);
                    } else {
                        console.error('Validasi gagal: Unknown error');
                    }
                } else {
                    progressBar.style.backgroundColor = '#F15A42';
                    progressBar.textContent = 'Gagal';
                    console.error('Upload gagal. Status:', xhr.status);
                }
            };

            xhr.onerror = function() {
                submitBtn.disabled = false;
                progressBar.style.backgroundColor = '#F15A42';
                clearInterval(manualProgressInterval);
                alert('Terjadi kesalahan jaringan saat mengupload.');
            };

            xhr.send(formData);
        });
    </script>
@endpush
