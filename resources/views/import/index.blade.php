@extends('layouts.dashboard')

@section('body')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Tabel Data Inflasi</h2>
        <a href="{{ route('upload.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            + Tambah Data
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-4">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="border border-gray-300 px-4 py-2">No</th>
                    <th class="border border-gray-300 px-4 py-2">Nama Tabel</th>
                    <th class="border border-gray-300 px-4 py-2">Jenis</th>
                    <th class="border border-gray-300 px-4 py-2">Upload Oleh</th>
                    <th class="border border-gray-300 px-4 py-2">Periode</th>
                    <th class="border border-gray-300 px-4 py-2">Upload Saat</th>
                    <th class="border border-gray-300 px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($uploads as $index => $upload)
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->data_name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->category }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->username }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->period }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $upload->created_at->format('d-m-Y H:i') }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="{{ route('upload.show', $upload->data_name) }}"
                               class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600">
                                Lihat Data
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($uploads->isEmpty())
            <p class="text-center text-gray-500 mt-4">Belum ada data yang diupload.</p>
        @endif
    </div>
</div>
@endsection
