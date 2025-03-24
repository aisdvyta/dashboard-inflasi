@extends('layouts.dashboard')

@section('body')
    <h2 class="text-4xl font-bold text-biru1 p-10 pl-24">Silahkan
        <span class="text-kuning1">isi form</span>
        untuk menambahkan Data!
    </h2>

    <div class="max-w-lg bg-white shadow-md rounded-lg p-6 ml-24">
        <form action="{{ route('manajemen-data-inflasi.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            <div class="mb-4 text-left">
                <label for="nama" class="block text-biru1 font-semibold">Username Upload</label>
                <input type="text" id="nama" name="nama" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5">
            </div>

            <div class="mb-4 text-left">
                <label for="periode" class="block text-biru1 font-semibold">
                    Pilih Periode Data <span class="text-gray-500 font-normal">(MM/YYYY)</span>
                </label>
                <input type="month" id="periode" name="periode" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5">
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
                            <input type="radio" name="jenis_data_inflasi" value="ASEM3" required
                                class="form-radio text-biru1 checked:text-biru1 focus:ring-biru1">
                            <span class="text-biru1">ASEM 2</span>
                        </label>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label>
                            <input type="radio" name="jenis_data_inflasi" value="ASEM2" required
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

            <div>
                <label class="block text-biru1 font-medium mb-1">Upload Data</label>
                <input type="file" name="file" accept=".xlsx" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5">
                <label class="block text-xs text-biru1 font-light mb-1">Pastikan file data inflasi memiliki format excel
                    (.xlsx)</label>
            </div>

            <button type="submit"
                class="w-full bg-biru1 hover:bg-biru4 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                Submit
            </button>
        </form>
    </div>
@endsection
