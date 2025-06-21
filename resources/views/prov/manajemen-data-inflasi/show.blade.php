{{-- filepath: d:\Kuliah\New folder (2)\dashboard-inflasi\resources\views\prov\manajemen-data-inflasi\show.blade.php --}}
@extends('layouts.dashboard')

@section('body')
<div class="container mx-auto p-6">
    <h2 class="text-4xl font-bold text-biru1 mb-4"> {{ $upload->nama }}</h2>

    <div class="mt-4">
        <form action="{{ route('manajemen-data-inflasi.show', $upload->nama) }}" method="GET"
            class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-lg w-80">
            <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Ikon Search" class="h-5 w-5">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari disini"
            class="text-sm w-full text-biru1 focus:outline-none" />
            <button type="submit" class="hidden">Cari</button>
        </form>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-xl px-4 py-2 mt-4">
        <table class="w-full">
            <thead class="border-b border-gray-300">
                <tr class="font-normal text-sm text-biru1">
                    <th class="py-2">No</th>
                    <th class="py-2">Kode Kota</th>
                    <th class="py-2">Nama Kota</th>
                    <th class="py-2">Kode Komoditas</th>
                    <th class="py-2">Nama Komoditas</th>
                    <th class="py-2">Flag</th>
                    <th class="py-2">Tingkat Inflasi <br>(M-to-M)</th>
                    <th class="py-2">Tingkat Inflasi <br>(Y-to-D)</th>
                    <th class="py-2">Tingkat Inflasi <br>(Y-on-Y)</th>
                    <th class=" py-2">Andil Inflasi <br>(M-to-M)</th>
                    <th class=" py-2">Andil Inflasi <br>(Y-to-D)</th>
                    <th class=" py-2">Andil Inflasi <br>(Y-on-Y)</th>
                </tr>
            </thead>
            <tbody class="mt-2 border-b border-gray-300">
                @foreach ($details as $detail)
                    <tr class="font-normal text-sm text-biru1 capitalize hover:bg-abubiru transition-colors duration-200">
                        <td class="px-4 py-2">
                            {{ $loop->iteration + ($details->currentPage() - 1) * $details->perPage() }}</td>
                        <td class="text-center px-4 py-2">{{ $detail->id_wil }}</td>
                        <td class="px-4 py-2">{{ ucwords(strtolower($detail->satker->nama_satker ?? 'Tidak Diketahui')) }}</td>
                        <td class="px-4 py-2">{{ $detail->id_kom }}</td>
                        <td class="px-4 py-2">{{ ucwords(strtolower($detail->komoditas->nama_kom ?? 'Tidak Diketahui')) }}</td>
                        <td class="text-center px-4 py-2">{{ $detail->id_flag }}</td>
                        <td class="text-center px-4 py-2">{{ $detail->inflasi_MtM }}</td>
                        <td class="text-center px-4 py-2">{{ $detail->inflasi_YtD }}</td>
                        <td class="text-center px-4 py-2">{{ $detail->inflasi_YoY }}</td>
                        <td class="text-center px-4 py-2">{{ $detail->andil_MtM }}</td>
                        <td class="text-center px-4 py-2">{{ $detail->andil_YtD }}</td>
                        <td class="text-center px-4 py-2">{{ $detail->andil_YoY }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $details->links('components.pagination') }}
        </div>
    </div>
</div>
@endsection

