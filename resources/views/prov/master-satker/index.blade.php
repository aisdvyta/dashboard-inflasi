@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex-col justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru1 mb-4">Tabel
                <span class="font-bold text-kuning1">Manajemen Master Satker</span>
            </h2>

            <div class="flex items-center justify-between gap-4">
                <!-- Search Bar -->
                <form action="{{ route('master-satker.index') }}" method="GET"
                    class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-lg w-80">
                    <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Ikon Search" class="h-5 w-5">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari disini"
                        class="text-sm w-full text-biru1 focus:outline-none" />
                    <button type="submit" class="hidden">Cari</button>
                </form>

                <!-- Tombol Tambah Satker -->
                @include('components.modaTambahSatker', [
                    'fileName' => 'master-satker',
                    'formAction' => route('master-satker.store'),
                ])

                <a href="javascript:void(0)"
                    onclick="document.getElementById('modalTambahSatker').classList.remove('hidden')"
                    class="flex items-center gap-2 px-2 py-2 rounded-lg bg-kuning1 text-biru1 hover:bg-biru4 hover:text-white group transition duration-300">
                    <img src="{{ asset('images/adminProv/masterSatker/btambahIcon.svg') }}" alt="Ikon Tambah Akun"
                        class="h-6 w-6 icon group-hover:hidden transition duration-100"
                        data-hover="{{ asset('images/adminProv/masterSatker/ptambahIcon.svg') }}"
                        data-default="{{ asset('images/adminProv/masterSatker/btambahIcon.svg') }}">
                    <img src="{{ asset('images/adminProv/masterSatker/ptambahIcon.svg') }}" alt="Ikon Tambah Akun Hover"
                        class="h-6 w-6 hidden group-hover:block transition duration-100">
                    <span
                        class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">
                        Tambah Satker</span>
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 z-10">
            <table class="w-full ">
                <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                    <tr class="text-biru1">
                        <th class="px-4 py-2 text-center">No</th>
                        <th class="px-4 py-2 text-center">Kode Satker</th>
                        <th class="px-4 py-2 text-left">Nama Satker</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($satkers as $index => $satker)
                        <tr>
                            <td class="px-4 py-2 text-center">{{ $loop->iteration + ($satkers->currentPage() - 1) * $satkers->perPage() }}</td>
                            <td class="px-4 py-2 text-center">{{ $satker->kode_satker }}</td>
                            <td class="px-4 py-2 text-left">{{ $satker->nama_satker }}</td>
                            <td class="px-4 py-2">
                                <div class="flex place-content-center gap-3">
                                    <!-- Tombol Edit -->
                                    @include('components.modaEditSatker', [
                                        'fileName' => 'master-satker',
                                    ])
                                    <button type="button" onclick="openModalEditSatker('{{ $satker->kode_satker }}')"
                                        class="flex items-center gap-1 bg-biru1 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 transition duration-100 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/editIcon.svg') }}" alt="Edit Icon"
                                            class="h-5 w-5">
                                        Edit Satker
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <button type="button" onclick="openModal('{{ $satker->kode_satker }}')"
                                        class="flex items-center gap-1 bg-merah1 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 transition duration-100 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/deleteIcon.svg') }}" alt="Delete Icon"
                                            class="h-5 w-5">
                                        Hapus Satker
                                    </button>
                                    @include('components.modaKonfirmasiHapus', [
                                        'id' => $satker->kode_satker,
                                        'folderName' => 'master-satker',
                                        'formAction' => route('master-satker.destroy', $satker->kode_satker),
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
                        </tr>>
                    @endforelse
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="mt-4">
                {{ $satkers->links('components.pagination') }}
            </div>
        </div>
    </div>
@endsection
