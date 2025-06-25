{{-- filepath: d:\Kuliah\New folder (2)\dashboard-inflasi\resources\views\prov\manajemen-data-inflasi\show.blade.php --}}
@extends('layouts.dashboard')

@section('body')
<div class="container p-6 mx-auto">
    <h2 class="mb-4 text-4xl font-bold text-biru1"> {{ $upload->nama }}</h2>

    <div class="mt-4">
        <form action="{{ route('manajemen-data-inflasi.show', $upload->nama) }}" method="GET"
            class="flex gap-2 items-center px-4 py-2 w-80 bg-white rounded-lg shadow-lg">
            <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Ikon Search" class="w-5 h-5">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari disini"
            class="w-full text-sm text-biru1 focus:outline-none" />
            <button type="submit" class="hidden">Cari</button>
        </form>
    </div>

    <div class="overflow-x-auto px-4 py-2 mt-4 bg-white rounded-xl shadow-md">
        <table class="w-full">
            <thead class="border-b border-gray-300">
                <tr class="text-sm font-normal text-biru1">
                    <th class="py-2">No</th>
                    <th class="py-2">Kode Kota</th>
                    <th class="py-2">Nama Kota</th>
                    <th class="py-2">Kode Komoditas</th>
                    <th class="py-2">Nama Komoditas</th>
                    <th class="py-2">Flag</th>
                    <th class="py-2">Tingkat Inflasi <br>(M-to-M)</th>
                    <th class="py-2">Tingkat Inflasi <br>(Y-to-D)</th>
                    <th class="py-2">Tingkat Inflasi <br>(Y-on-Y)</th>
                    <th class="py-2">Andil Inflasi <br>(M-to-M)</th>
                    <th class="py-2">Andil Inflasi <br>(Y-to-D)</th>
                    <th class="py-2">Andil Inflasi <br>(Y-on-Y)</th>
                </tr>
            </thead>
            <tbody class="mt-2 border-b border-gray-300">
                @foreach ($details as $detail)
                    <tr class="text-sm font-normal capitalize transition-colors duration-200 text-biru1 hover:bg-abubiru">
                        <td class="px-4 py-2">
                            {{ $loop->iteration + ($details->currentPage() - 1) * $details->perPage() }}</td>
                        <td class="px-4 py-2 text-center">{{ $detail->id_wil }}</td>
                        <td class="px-4 py-2">{{ ucwords(strtolower($detail->satker->nama_satker ?? 'Tidak Diketahui')) }}</td>
                        <td class="px-4 py-2">{{ $detail->id_kom }}</td>
                        <td class="px-4 py-2">{{ ucwords(strtolower($detail->komoditas->nama_kom ?? 'Tidak Diketahui')) }}</td>
                        <td class="px-4 py-2 text-center">{{ $detail->id_flag }}</td>
                        <td class="px-4 py-2 text-center">{{ $detail->inflasi_MtM }}</td>
                        <td class="px-4 py-2 text-center">{{ $detail->inflasi_YtD }}</td>
                        <td class="px-4 py-2 text-center">{{ $detail->inflasi_YoY }}</td>
                        <td class="px-4 py-2 text-center">{{ $detail->andil_MtM }}</td>
                        <td class="px-4 py-2 text-center">{{ $detail->andil_YtD }}</td>
                        <td class="px-4 py-2 text-center">{{ $detail->andil_YoY }}</td>
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

