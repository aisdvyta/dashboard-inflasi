@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex-col justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru1 mb-4">Tabel Manajemen<span class="font-bold text-kuning1"> Komoditas Utama</span></h2>

            <div class="flex items-center justify-between gap-4">
                <!-- Search Bar -->
                <form action="{{ route('komoditas-utama.index') }}" method="GET"
                    class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-lg w-80">
                    <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Ikon Search" class="h-5 w-5">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari disini"
                        class="text-sm w-full text-biru1 focus:outline-none" />
                    <button type="submit" class="hidden">Cari</button>
                </form>

                <!-- Tombol Tambah Komoditas Utama -->
                @include('components.modaTambahKomUtama', [
                    'fileName' => 'komoditas-utama',
                    'formAction' => route('komoditas-utama.storeKomUtama'),
                ])

                <a href="javascript:void(0)"
                    onclick="document.getElementById('modalTambahKomUtama').classList.remove('hidden')"
                    class="flex items-center gap-2 px-2 py-2 rounded-lg bg-kuning1 text-biru1 hover:bg-biru4 hover:text-white group transition duration-300">
                    <img src="{{ asset('images/adminProv/masterSatker/btambahIcon.svg') }}" alt="Ikon Tambah Akun"
                        class="h-6 w-6 icon group-hover:hidden transition duration-100"
                        data-hover="{{ asset('images/adminProv/masterSatker/ptambahIcon.svg') }}"
                        data-default="{{ asset('images/adminProv/masterSatker/btambahIcon.svg') }}">
                    <img src="{{ asset('images/adminProv/masterSatker/ptambahIcon.svg') }}" alt="Ikon Tambah Akun Hover"
                        class="h-6 w-6 hidden group-hover:block transition duration-100">
                    <span
                        class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">
                        Tambah Komoditas Utama</span>
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 z-10">
            <table class="w-full">
                <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                    <tr class="text-biru1">
                        <th class="px-4 py-2 text-center">No</th>
                        <th class="px-8 py-2 text-left">Kode Komoditas</th>
                        <th class="px-4 py-2 text-left">Nama Komoditas</th>
                        <th class="px-8 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($komoditasUtama as $index => $item)
                        <tr>
                            <td class="px-4 py-2 text-center">{{ $loop->iteration + ($komoditasUtama->currentPage() - 1) * $komoditasUtama->perPage() }}</td>
                            <td class="px-8 py-2 text-left">{{ $item->kode_kom }}</td>
                            <td class="px-4 py-2 text-left">{{ $item->nama_kom }}</td>
                            <td class="px-8 py-2 text-center">
                                <div class="flex place-content-center gap-3">
                                    <!-- Tombol Edit -->
                                    <!--<button type="button" onclick="openModalEditKomUtama('{{ $item->kode_kom }}')"-->
                                    <!--    class="flex items-center gap-1 bg-biru1 text-white px-5 py-1 rounded-lg shadow-lg hover:-translate-y-1 transition duration-100 text-sm font-normal">-->
                                    <!--    <img src="{{ asset('images/adminProv/editIcon.svg') }}" alt="Edit Icon"-->
                                    <!--        class="h-5 w-5">-->
                                    <!--    Edit-->
                                    <!--</button>-->
                                    <!-- Tombol Hapus -->
                                    <button type="button" onclick="openModalHapusKomUtama('{{ $item->kode_kom }}', '{{ $item->nama_kom }}')"
                                        class="flex items-center gap-1 bg-merah1 text-white px-5 py-1 rounded-lg shadow-lg hover:-translate-y-1 transition duration-100 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/deleteIcon.svg') }}" alt="Delete Icon"
                                            class="h-5 w-5">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Tidak ada data komoditas utama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $komoditasUtama->links('components.pagination') }}
            </div>
        </div>
    </div>
    @include('components.modaEditKomUtama')
    @include('components.modaKonfirmasiHapusKomUtama')

    <!-- Modal Peringatan Komoditas Utama Sudah Ada -->
    <div id="modalPeringatanKomUtama" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 {{ session('error_kom_utama') ? '' : 'hidden' }}">
        <div class="bg-white p-6 rounded-xl shadow-lg w-fit relative">
            <button type="button" onclick="document.getElementById('modalPeringatanKomUtama').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-xl">&times;</button>
            <h2 class="text-xl font-bold text-merah1 mb-4">Peringatan</h2>
            <p class="mb-6 text-biru1">{{ session('error_kom_utama') }}</p>
            <div class="flex justify-end">
                <button type="button" onclick="document.getElementById('modalPeringatanKomUtama').classList.add('hidden')" class="bg-biru4 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Tutup
                </button>
            </div>
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
