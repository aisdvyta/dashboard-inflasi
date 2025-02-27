@extends('layouts.dashboard')

@section('body')
<div class="container mx-auto p-6 relative">
    <!-- Elemen Batik -->

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl font-bold text-biru4"><span class="font-bold text-biru1">Tabel</span> Data Inflasi</h2>
        <div class="flex items-center gap-2 px-2 py-1 bg-white rounded-xl shadow-lg">
            <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Search Icon" class="h-5 w-5">
            <input type="text" name="search" placeholder="Cari disini">
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-4 z-10">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="border border-gray-300 px-4 py-2">No</th>
                    <th class="border border-gray-300 px-4 py-2">Nama Tabel</th>
                    <th class="border border-gray-300 px-4 py-2">Jenis</th>
                    <th class="border border-gray-300 px-4 py-2">Upload Oleh</th>
                    <th class="border border-gray-300 px-4 py-2">Periode</th>
                    <th class="border border-gray-300 px-4 py-2">Upload Saat</th>
                    <th class="border border-gray-300 px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($uploads as $index => $upload)
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->data_name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->category }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->username }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->period }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->created_at->format('d-m-Y H:i') }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <div class="flex gap-3">
                                <a href="{{ route('manajemen-data-inflasi.show', $upload->data_name) }}"
                                    class="flex items-center gap-1 bg-biru4 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1">
                                    <img src="{{ asset('images/sidebar/eyeIcon.svg') }}" alt="Download Icon" class="h-5 w-5">
                                    Lihat Data
                                </a>
                                <a href="{{ route('manajemen-data-inflasi.show', $upload->data_name) }}"
                                    class="flex items-center gap-1 bg-kuning1 text-biru1 px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1">
                                    <img src="{{ asset('images/sidebar/downloadIcon.svg') }}" alt="Download Icon" class="h-5 w-5">
                                    Unduh Data
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($uploads->isEmpty())
            <p class="text-center text-gray-500 mt-4">Belum ada data yang diupload.</p>
        @endif
    </div>
</div>
@endsection
