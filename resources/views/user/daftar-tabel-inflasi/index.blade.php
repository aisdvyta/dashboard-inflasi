@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru4"><span class="font-bold text-biru1">Tabel</span> Data Inflasi</h2>
            <div class="flex items-center gap-2 px-4 py-1 bg-white rounded-xl shadow-lg">
                <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Search Icon" class="h-5 w-10">
                <input type="text" name="search" placeholder="Cari disini">
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 z-10">
            <table class="w-full">
                <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                    <tr class="text-biru1">
                        <th class="px-4 py-2 text-center">No.</th>
                        <th class="px-4 py-2 text-left">Nama Data</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 10; $i++)
                        <tr>
                            <td class="px-4 py-4 text-center">{{ $i }}</td>
                            <td class="px-4 py-2 hover:underline hover:text-biru4">Data Inflasi Provinsi Jawa Timur Menurut Kelompok Pengeluaran Bulan Januari 2025</td>
                            <td class="px-4 py-2">
                                <div class="flex place-content-center gap-3">
                                    <a href="#"
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
                    @endfor
                </tbody>
            </table>

            {{-- @if ($uploads->isEmpty())
                <p class="text-center text-gray-500 mt-4">Belum ada data yang diupload.</p>
            @endif --}}
        </div>
    </div>
@endsection
