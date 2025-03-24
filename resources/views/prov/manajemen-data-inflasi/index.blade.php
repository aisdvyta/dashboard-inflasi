@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex-col justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru1 mb-4">Tabel <span class="font-bold text-kuning1">Manajemen Data</span>
                Inflasi</h2>
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-lg w-80">
                    <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Ikon Search" class="h-5 w-5">
                    <input type="text" name="search" placeholder="Cari disini"
                        class="text-sm w-80 text-biru1 focus:outline-none">
                </div>
                <a href="{{ route('manajemen-data-inflasi.create') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded-lg bg-kuning1 text-biru1 hover:bg-biru4 hover:text-white group transition duration-300"
                    data-page="tabel">
                    <img src="{{ asset('images/adminProv/baddDataIcon.svg') }}" alt="Ikon Data Inflasi"
                        class="h-6 w-6 icon group-hover:hidden transition duration-100"
                        data-hover="{{ asset('images/adminProv/paddDataIcon.svg') }}"
                        data-default="{{ asset('images/adminProv/baddDataIcon.svg') }}">
                    <img src="{{ asset('images/adminProv/paddDataIcon.svg') }}" alt="Ikon Data Inflasi Hover"
                        class="h-6 w-6 hidden group-hover:block transition duration-100">
                    <span
                        class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">Tambah Data Inflasi</span>
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 z-10">
            <table class="w-full">
                <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                    <tr class="text-biru1">
                        <th class="px-4 py-2 text-left">No.</th>
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
                                <span
                                    class="px-2 py-1 rounded-full text-white {{ $upload->jenis_data_inflasi == 'ATAP' ? 'bg-yellow-500' : 'bg-green-500' }}">
                                    {{ $upload->jenis_data_inflasi }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($upload->upload_at)->format('d/m/Y') }}</td>
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

<script>
    document.querySelectorAll('.icon').forEach(icon => {
        const defaultSrc = icon.getAttribute('data-default');
        const hoverSrc = icon.getAttribute('data-hover');
        const parentLink = icon.closest('a');

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
    });
</script>
