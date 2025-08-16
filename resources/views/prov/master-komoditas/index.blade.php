<!-- filepath: d:\Kuliah\New folder\coding\dashboard-inflasi\resources\views\prov\master-komoditas\index.blade.php -->
@extends('layouts.dashboard')
@include('components.modaEditKomoditas')
@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex-col justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru1 mb-4">Tabel Manajemen
                <span class="font-bold text-kuning1">Master Komoditas</span>
            </h2>

            <div class="flex items-center justify-between gap-4">
                <!-- Search Bar -->
                <form action="{{ route('master-komoditas.index') }}" method="GET"
                    class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-lg w-80">
                    <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Ikon Search" class="h-5 w-5">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari disini"
                        class="text-sm w-full text-biru1 focus:outline-none" />
                    <button type="submit" class="hidden">Cari</button>
                </form>

                <a href="{{ route('master-komoditas.create') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded-lg bg-kuning1 text-biru1 hover:bg-biru4 hover:text-white group transition duration-300">
                    <img src="{{ asset('images/adminProv/masterSatker/btambahIcon.svg') }}" alt="Ikon Tambah Komoditas"
                        class="h-6 w-6">
                    <span
                        class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">
                        Tambah Komoditas</span>
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 z-10">
            <table class="w-full ">
                <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                    <tr class="text-biru1">
                        <th class="px-4 py-2 text-center">No</th>
                        <th class="px-8 py-2 text-left">Kode Komoditas</th>
                        <th class="px-4 py-2 text-left">Nama Komoditas</th>
                        <th class="px-4 py-2 text-center">Flag</th>
                        <th class="px-8 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($komoditas as $index => $komoditasItem)
                        <tr>
                            <td class="px-4 py-2 text-center">
                                {{ $loop->iteration + ($komoditas->currentPage() - 1) * $komoditas->perPage() }}</td>
                            <td class="px-8 py-2 text-left">{{ $komoditasItem->kode_kom }}</td>
                            <td class="px-4 py-2 text-left">{{ $komoditasItem->nama_kom }}</td>
                            <td class="px-4 py-2 text-center">{{ $komoditasItem->flag }}</td>
                            <td class="px-8 py-2">
                                <div class="flex place-content-center gap-3">
                                    <!-- Tombol Edit -->
                                    <button type="button" onclick="openModalEditSatker('{{ $komoditasItem->kode_kom }}')"
                                        class="flex items-center gap-1 bg-biru1 text-white px-5 py-1 rounded-lg shadow-lg hover:-translate-y-1 transition duration-100 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/editIcon.svg') }}" alt="Edit Icon"
                                            class="h-5 w-5">
                                        Edit
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <button type="button" onclick="openModal('{{ $komoditasItem->kode_kom }}')"
                                        class="flex items-center gap-1 bg-merah1 text-white px-5 py-1 rounded-lg shadow-lg hover:-translate-y-1 transition duration-100 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/deleteIcon.svg') }}" alt="Delete Icon"
                                            class="h-5 w-5">
                                        Hapus
                                    </button>
                                    @include('components.modaKonfirmasiHapus', [
                                        'id' => $komoditasItem->kode_kom,
                                        'folderName' => 'master-komoditas',
                                        'formAction' => route('master-komoditas.destroy', $komoditasItem->kode_kom),
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                @if ($search)
                                    Tidak ada hasil untuk pencarian "{{ $search }}".
                                @else
                                    Tidak ada data komoditas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $komoditas->links('components.pagination') }}
            </div>
        </div>
    </div>
@endsection
