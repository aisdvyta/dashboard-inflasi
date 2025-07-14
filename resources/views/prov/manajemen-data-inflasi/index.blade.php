@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex-col justify-between items-center mb-4">
            <h2 class="text-3xl font-bold text-biru1 mb-4">Tabel Manajemen <span class="font-bold text-kuning1"> Data Inflasi</span>
            </h2>
            <div class="flex items-center justify-between gap-4">
                <form action="{{ route('manajemen-data-inflasi.index') }}" method="GET"
                    class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-lg w-80">
                    <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Ikon Search" class="h-5 w-5">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari disini"
                        class="text-sm w-full text-biru1 focus:outline-none" />
                    <button type="submit" class="hidden">Cari</button>
                </form>
                @if(Auth::user()->id_role == 1)
                <a href="{{ route('manajemen-data-inflasi.create') }}"
                    class="flex items-center gap-2 px-2 py-2 rounded-lg bg-kuning1 text-biru1 hover:bg-biru4 hover:text-white group transition duration-300"
                    data-page="tabel">
                    <img src="{{ asset('images/adminProv/manajemenData/baddDataIcon.svg') }}" alt="Ikon Data Inflasi"
                        class="h-6 w-6 icon group-hover:hidden transition duration-100"
                        data-hover="{{ asset('images/adminProv/manajemenData/paddDataIcon.svg') }}"
                        data-default="{{ asset('images/adminProv/manajemenData/baddDataIcon.svg') }}">
                    <img src="{{ asset('images/adminProv/paddDataIcon.svg') }}" alt="Ikon Data Inflasi Hover"
                        class="h-6 w-6 hidden group-hover:block transition duration-100">
                    <span
                        class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">Tambah Data Inflasi</span>
                </a>
                @endif
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
                        @if(Auth::user()->id_role == 1)
                        <th class="px-4 py-2 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($uploads as $index => $upload)
                        <tr>
                            <td class="px-4 py-4 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 hover:underline hover:text-biru4">
                                <a href="{{ route('manajemen-data-inflasi.show', $upload->nama) }}">
                                    {{ $upload->nama }}
                                </a>
                            </td>
                            <td class="px-6 py-2 text-center font-semibold">
                                <span
                                    class="px-6 py-1 rounded-full text-biru1 {{ $upload->jenis_data_inflasi == 'ATAP' ? 'bg-kuning2' : 'bg-hijau2' }}">
                                    {{ $upload->jenis_data_inflasi }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($upload->upload_at)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-center">
                                <div
                                    class="inline-flex items-center gap-0.5 bg-gray-200 text-biru1 px-3 py-1 rounded-full w-fit mx-auto">
                                    <img src="{{ asset('images/adminProv/manajemenData/usernameIcon.svg') }}"
                                        alt="User Icon" class="h-6 w-6">
                                    <span
                                        class="text-sm font-semibold"></span>{{ $upload->pengguna->nama ?? 'Tidak Diketahui' }}</span>
                                </div>
                            </td>
                            @if(Auth::user()->id_role == 1)
                            <td class="px-4 py-2">
                                <div class="flex place-content-center gap-3">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('manajemen-data-inflasi.edit', $upload->id) }}"
                                        class="flex items-center gap-1 bg-biru1 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/editIcon.svg') }}" alt="Edit Icon"
                                            class="h-5 w-5">
                                        Edit Data
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <button type="button" onclick="openModal('{{ $upload->id }}')"
                                        class="flex items-center gap-1 bg-merah1 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 text-sm font-normal">
                                        <img src="{{ asset('images/adminProv/deleteIcon.svg') }}" alt="Delete Icon"
                                            class="h-5 w-5">
                                        Hapus Data
                                    </button>

                                    @include('components.modaKonfirmasiHapus', [
                                        'id' => $upload->id,
                                        'folderName' => 'manajemen-data-inflasi',
                                        'formAction' => route('manajemen-data-inflasi.destroy', $upload->id),
                                    ])
                                </div>
                            </td>
                            @endif
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
            <div class="mt-4">
                {{ $uploads->links('components.pagination') }}
            </div>
        </div>
    </div>
@endsection

<div id="modalBerhasil" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="p-6 bg-white shadow-lg rounded-xl w-fit">
        <div class="flex justify-center mb-2">
            <img src="{{ asset('images/moda/berhasilIcon.svg') }}" alt="Berhasil Icon" class="w-8 h-8">
        </div>
        <h2 class="text-2xl font-[650] text-biru1 text-center">Yay <span class="text-hijau">Berhasil</span></h2>
                    <div class="mt-2 mb-6">
                <p class="mt-2 text-base text-center text-biru1">
                    {{ session('message', 'Data berhasil diupload!') }}
                </p>
            </div>
        <div class="flex justify-center mt-4">
            <button
                class="px-8 py-2 font-normal text-white transition-all duration-200 rounded-lg shadow-lg bg-hijau hover:-translate-y-1"
                onclick="document.getElementById('modalBerhasil').classList.add('hidden')">Oke</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek query string status=success
        if (new URLSearchParams(window.location.search).get('status') === 'success') {
            document.getElementById('modalBerhasil').classList.remove('hidden');
            // Hapus query string agar modal tidak muncul lagi saat reload
            if (window.history.replaceState) {
                const url = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, url);
            }
        }

        // Cek session status untuk update berhasil
        @if(session('status') === 'success')
            document.getElementById('modalBerhasil').classList.remove('hidden');
        @endif
    });
</script>
@endpush


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
