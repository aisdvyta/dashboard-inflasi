@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru4"><span class="font-bold text-biru1">Tabel</span> Data Inflasi</h2>
            <div class="flex items-center gap-2 px-4 py-1 bg-white rounded-xl shadow-lg">
                <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Search Icon" class="h-5 w-10">
                <form method="GET" action="{{ route('daftar-tabel-inflasi.index') }}" class="flex items-center">
                    <input type="text" name="search" placeholder="Cari disini" value="{{ $search }}" class="border-none outline-none bg-transparent">
                </form>
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
                    @forelse ($uploads as $index => $upload)
                        <tr>
                            <td class="px-4 py-4 text-center">{{ $uploads->firstItem() + $index }}</td>
                            <td class="px-4 py-2 hover:underline hover:text-biru4">{{ $upload->display_name }}</td>
                            <td class="px-4 py-2">
                                <div class="flex place-content-center gap-3">
                                    <a href="{{ route('daftar-tabel-inflasi.show', $upload->id) }}"
                                        class="flex items-center gap-1 bg-biru4 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1">
                                        <img src="{{ asset('images/eyeIcon.svg') }}" alt="View Icon"
                                            class="h-5 w-5">
                                        Lihat Data
                                    </a>
                                    <a href="{{ route('daftar-tabel-inflasi.download', $upload->id) }}"
                                        class="flex items-center gap-1 bg-hijaumuda text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1">
                                        <img src="{{ asset('images/excelIcon.svg') }}" alt="Download Icon"
                                            class="h-5 w-5">
                                        Unduh Data
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                Belum ada data inflasi ATAP yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($uploads->hasPages())
                <div class="mt-4">
                    {{ $uploads->appends(['search' => $search])->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
