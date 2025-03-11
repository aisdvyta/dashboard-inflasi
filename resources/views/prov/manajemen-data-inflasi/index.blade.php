@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <!-- Elemen Batik -->

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru4"><span class="font-bold text-biru1">Tabel</span> Data Inflasi</h2>
            <div class="flex items-center gap-2 px-4 py-1 bg-white rounded-xl shadow-lg">
                <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Search Icon" class="h-5 w-10">
                <input type="text" name="search" placeholder="Cari disini">
            </div>
            <a href="{{ route('manajemen-data-inflasi.create') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-yellow-600 transition duration-300">
                Tambah Data Inflasi
            </a>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 z-10">
            <table class="w-full">
                <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                    <tr class="text-biru1">
                        <th class="px-4 py-2 text-center">No.</th>
                        <th class="px-4 py-2 text-left">Nama Data</th>
                        <th class="px-4 py-2 text-center">Kategori</th>
                        <th class="px-4 py-2 text-center">Tanggal Upload</th>
                        <th class="px-4 py-2 text-center">Username Upload</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($uploads as $index => $upload)
                        <tr>
                            <td class="px-4 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 hover:underline hover:text-biru4">{{ $upload->nama }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="px-2 py-1 rounded-full text-white {{ $upload->jenis_data_inflasi == 'ATAP' ? 'bg-yellow-500' : 'bg-green-500' }}">
                                    {{ $upload->jenis_data_inflasi }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($upload->upload_at)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-center">Fulan123</td>
                            <td class="px-4 py-2">
                                <div class="flex place-content-center gap-3">
                                    <a href="{{ route('manajemen-data-inflasi.show', $upload->nama) }}"
                                        class="flex items-center gap-1 bg-biru4 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1">
                                        <img src="{{ asset('images/sidebar/eyeIcon.svg') }}" alt="View Icon"
                                            class="h-5 w-5">
                                        Lihat Data
                                    </a>
                                    <a href="#"
                                        class="flex items-center gap-1 bg-kuning1 text-biru1 px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1">
                                        <img src="{{ asset('images/sidebar/downloadIcon.svg') }}" alt="Download Icon"
                                            class="h-5 w-5">
                                        Unduh Data
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">Belum ada data yang diupload.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
