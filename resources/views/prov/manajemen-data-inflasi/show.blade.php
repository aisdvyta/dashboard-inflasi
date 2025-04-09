{{-- filepath: d:\Kuliah\New folder (2)\dashboard-inflasi\resources\views\prov\manajemen-data-inflasi\show.blade.php --}}
@extends('layouts.dashboard')

@section('body')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">Detail Data: {{ $upload->nama }}</h2>

    <div class="bg-white shadow-md rounded-lg p-4">
        <p><strong>Uploader:</strong> {{ $upload->pengguna->nama ?? 'Tidak Diketahui' }}</p>
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($upload->periode)->format('F Y') }}</p>
        <p><strong>Kategori:</strong> {{ $upload->jenis_data_inflasi }}</p>
    </div>

    <h3 class="text-xl font-semibold mt-6">Isi Data:</h3>
    <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4 mt-4">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Tahun</th>
                    <th class="border border-gray-300 px-4 py-2">Bulan</th>
                    <th class="border border-gray-300 px-4 py-2">Kode Kota</th>
                    <th class="border border-gray-300 px-4 py-2">Nama Kota</th>
                    <th class="border border-gray-300 px-4 py-2">Kode Komoditas</th>
                    <th class="border border-gray-300 px-4 py-2">Nama Komoditas</th>
                    <th class="border border-gray-300 px-4 py-2">Flag</th>
                    <th class="border border-gray-300 px-4 py-2">Inflasi MtM</th>
                    <th class="border border-gray-300 px-4 py-2">Inflasi YtD</th>
                    <th class="border border-gray-300 px-4 py-2">Inflasi YoY</th>
                    <th class="border border-gray-300 px-4 py-2">Andil MtM</th>
                    <th class="border border-gray-300 px-4 py-2">Andil YtD</th>
                    <th class="border border-gray-300 px-4 py-2">Andil YoY</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $detail)
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($upload->periode)->year }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($upload->periode)->month }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->id_wil }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->satker->nama_satker ?? 'Tidak Diketahui' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->id_kom }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->komoditas->nama_kom ?? 'Tidak Diketahui' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->id_flag }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->inflasi_MtM }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->inflasi_YtD }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->inflasi_YoY }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->andil_MtM }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->andil_YtD }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $detail->andil_YoY }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
