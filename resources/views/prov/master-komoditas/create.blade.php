@extends('layouts.dashboard')

@section('body')
    <h2 class="text-4xl font-bold text-biru1 p-10 pl-24">
        Silahkan <span class="text-kuning1">Tambah Komoditas</span>
    </h2>

    <div class="max-w-2xl bg-white shadow-md rounded-lg p-6 ml-24">
        <form method="POST" action="{{ route('master-komoditas.store') }}" class="space-y-4">
            @csrf

            <!-- Pilih Flag -->
            <div class="mb-4">
                <label for="flag" class="block text-biru1 font-semibold mb-1">Pilih Tingkat Komoditas</label>
                <select id="flag" name="flag" required class="w-full p-2 rounded-2xl border border-biru5">
                    <option value="">-- Pilih Tingkatan --</option>
                    <option value="1">Kelompok</option>
                    <option value="2">Sub Kelompok</option>
                    <option value="3">Barang</option>
                </select>
            </div>

            <!-- Kelompok -->
            <div class="mb-4" id="kelompok-wrapper" style="display: none;">
                <label for="kelompok" class="block text-biru1 font-semibold mb-2">Pilih Kelompok</label>
                <select id="kelompok" name="kelompok" class="w-full mt-1 p-2 rounded-2xl border border-biru5">
                    <option value="" disabled selected>-- Pilih Kelompok --</option>
                    @foreach ($kelompok as $item)
                        <option value="{{ $item->kode_kom }}">{{ $item->nama_kom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sub Kelompok -->
            <div class="mb-4" id="subkelompok-wrapper" style="display: none;">
                <label for="sub_kelompok" class="block text-biru1 font-semibold mb-2">Pilih Subkelompok</label>
                <select id="sub_kelompok" name="sub_kelompok" class="w-full mt-1 p-2 rounded-2xl border border-biru5">
                    <option value="" disabled selected>-- Pilih Subkelompok --</option>
                    @foreach ($subKelompok as $item)
                        <option value="{{ $item->kode_kom }}" data-parent="{{ substr($item->kode_kom, 0, 2) }}">
                            {{ $item->nama_kom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kode Komoditas -->
            <div class="mb-4">
                <label for="kode" class="block text-biru1 font-semibold mb-1">Kode Komoditas</label>
                <div class="flex space-x-2">
                    <input type="text" id="kode_prefix" disabled
                        class="w-1/3 p-2 rounded-2xl border border-biru3 bg-gray-100 text-gray-700">
                    <input type="text" id="kode_suffix" name="kode" required maxlength="7"
                        class="w-2/3 p-2 rounded-2xl border border-biru5 focus:ring-biru5"
                        placeholder="Masukkan kode sesuai aturan flag">
                </div>
                @error('kode')
                    <small class="text-red-500 italic">{{ $message }}</small>
                @enderror
            </div>

            <!-- Nama Komoditas -->
            <div class="mb-4">
                <label for="nama" class="block text-biru1 font-semibold mb-1">Nama Komoditas</label>
                <input type="text" id="nama" name="nama" required
                    class="w-full p-2 rounded-2xl border border-biru5 focus:ring-biru5" value="{{ old('nama') }}">
                @error('nama')
                    <small class="text-red-500 italic">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-biru1 hover:bg-biru4 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                Simpan
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flagSelect = document.getElementById('flag');
            const kelompokWrapper = document.getElementById('kelompok-wrapper');
            const subKelompokWrapper = document.getElementById('subkelompok-wrapper');
            const kelompokSelect = document.getElementById('kelompok');
            const subKelompokSelect = document.getElementById('sub_kelompok');
            const kodePrefix = document.getElementById('kode_prefix');
            const kodeSuffix = document.getElementById('kode_suffix');

            const allKelompokOptions = [...kelompokSelect.options];
            const allSubKelompokOptions = [...subKelompokSelect.options];

            function resetDropdown(selectElement, placeholder) {
                selectElement.innerHTML = `<option disabled selected>${placeholder}</option>`;
            }

            flagSelect.addEventListener('change', function() {
                const flag = this.value;

                kodePrefix.value = '';
                kodeSuffix.value = '';
                kodeSuffix.maxLength = 7;

                kelompokWrapper.style.display = 'none';
                subKelompokWrapper.style.display = 'none';
                resetDropdown(kelompokSelect, '-- Pilih Kelompok --');
                resetDropdown(subKelompokSelect, '-- Pilih Subkelompok --');

                if (flag === '1') {
                    // Kelompok
                    kodePrefix.value = '';
                    kodeSuffix.maxLength = 2;
                }

                if (flag === '2') {
                    // Sub Kelompok
                    kelompokWrapper.style.display = 'block';
                    kodeSuffix.maxLength = 1;
                    allKelompokOptions.forEach(opt => {
                        if (opt.value !== '') kelompokSelect.appendChild(opt.cloneNode(true));
                    });
                }

                if (flag === '3') {
                    // Barang
                    kelompokWrapper.style.display = 'block';
                    subKelompokWrapper.style.display = 'block';
                    kodeSuffix.maxLength = 4;
                    allKelompokOptions.forEach(opt => {
                        if (opt.value !== '') kelompokSelect.appendChild(opt.cloneNode(true));
                    });
                }
            });

            kelompokSelect.addEventListener('change', function() {
                const selectedKelompok = this.value;
                const flag = flagSelect.value;

                if (flag === '2') {
                    // Sub Kelompok
                    kodePrefix.value = selectedKelompok;
                }

                if (flag === '3') {
                    // Barang
                    kodePrefix.value = selectedKelompok;
                    resetDropdown(subKelompokSelect, '-- Pilih Subkelompok --');
                    allSubKelompokOptions.forEach(opt => {
                        if (opt.getAttribute('data-parent') === selectedKelompok) {
                            subKelompokSelect.appendChild(opt.cloneNode(true));
                        }
                    });
                }
            });

            subKelompokSelect.addEventListener('change', function() {
                const selectedSub = this.value;
                const flag = flagSelect.value;

                if (flag === '3') {
                    kodePrefix.value = selectedSub;
                }
            });

            // Gabungkan kode prefix + suffix sebelum submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const fullKode = kodePrefix.value + kodeSuffix.value;
                kodeSuffix.value = fullKode;
            });
        });
    </script>
@endpush
