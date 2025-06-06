@extends('layouts.dashboard')

@section('body')
    <h2 class="text-4xl font-bold text-biru1 p-10 pl-24">Silahkan
        <span class="text-kuning1">edit form</span>
        untuk memperbarui Data!
    </h2>

    <div class="max-w-lg bg-white shadow-md rounded-lg p-6 ml-24">
        <form action="{{ route('manajemen-data-inflasi.update', $upload->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            @method('PUT')
            <div class="mb-4 text-left">
                <label for="nama" class="block text-biru1 font-semibold">Username Upload</label>
                <input type="text" id="nama" name="nama" value="{{ $upload->pengguna->nama }}" readonly
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 bg-gray-100 focus:ring-biru5">
            </div>

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
@endsection
