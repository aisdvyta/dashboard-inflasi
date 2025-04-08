@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex-col justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru1 mb-4">Tabel <span class="font-bold text-kuning1">Manajemen Akun</span> Tim Harga Statistik Distribusi</h2>

            <div class="flex items-center justify-between gap-4">
                <!-- Search Bar -->
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-lg w-80">
                    <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Ikon Search" class="h-5 w-5">
                    <input type="text" name="search" placeholder="Cari disini" class="text-sm w-full text-biru1 focus:outline-none">
                </div>

                <!-- Tombol Tambah Akun -->
                <a href="{{ route('manajemen-akun.create') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded-lg bg-kuning1 text-biru1 hover:bg-biru4 hover:text-white group transition duration-300">
                    <img src="{{ asset('images/adminProv/manajemenAkun/btambahkanAkun.svg') }}" alt="Ikon Tambah Akun"
                        class="h-6 w-6 icon group-hover:hidden transition duration-100">
                    <img src="{{ asset('images/adminProv/manajemenAkun/ptambahkanAkun.svg') }}" alt="Ikon Tambah Akun Hover"
                        class="h-6 w-6 hidden group-hover:block transition duration-100">
                    <span class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">Tambah Akun</span>
                </a>
            </div>
        </div>

        <!-- Modal Berhasil -->
        @include('components.modaBerhasil', ['fileName' => 'manajemen-akun'])

        <!-- Modal Gagal -->
        @include('components.modaGagal', ['fileName' => 'manajemen-akun'])

        <!-- Tabel Manajemen Akun -->
        <div class="bg-white shadow-md rounded-xl p-4 z-10">
            <table class="w-full">
                <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                    <tr class="text-biru1">
                        <th class="px-4 py-2 text-left">No.</th>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-center">Kode Satker</th>
                        <th class="px-4 py-2 text-left">Nama Satker</th>
                        <th class="px-4 py-2 text-center">Role</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td class="px-4 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $user->username }}</td>
                            <td class="px-4 py-2 text-center">{{ $user->satker->kode_satker ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $user->satker->nama_satker ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ ucfirst($user->role->nama_role ?? '-') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex place-content-center gap-3">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('manajemen-akun.edit', $user->id) }}"
                                        class="flex items-center gap-1 bg-biru1 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/editIcon.svg') }}" alt="Edit Icon" class="h-5 w-5">
                                        Edit Akun
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                        onclick="openModal('{{ $user->id }}')"
                                        class="flex items-center gap-1 bg-merah1 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/deleteIcon.svg') }}" alt="Delete Icon" class="h-5 w-5">
                                        Hapus Akun
                                    </button>

                                    @include('components.modaKonfirmasiHapus', [
                                        'id' => $user->id,
                                        'folderName' => 'manajemen-akun',
                                        'formAction' => route('manajemen-akun.destroy', $user->id),
                                    ])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">Belum ada akun yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if (session('status') === 'success')
            document.getElementById('modalBerhasil').classList.remove('hidden'); // Tampilkan modal berhasil
        @elseif (session('status') === 'error')
            document.getElementById('modalGagal').classList.remove('hidden'); // Tampilkan modal gagal
        @endif
    });

    parentLink.addEventListener('mouseenter', () => {
        icon.setAttribute('src', hoverSrc);
    });

    parentLink.addEventListener('mouseleave', () => {
        if (!parentLink.classList.contains('active')) {
            icon.setAttribute('src', defaultSrc);
        }
    });

    if (parentLink.classList.contains('active')) {
        icon.setAttribute('src', hoverSrc);
    }
</script>