@extends('layouts.dashboard')

@section('body')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">Detail Data: {{ $upload->data_name }}</h2>

    <div class="bg-white shadow-md rounded-lg p-4">
        <p><strong>Uploader:</strong> {{ $upload->username }}</p>
        <p><strong>Periode:</strong> {{ $upload->period }}</p>
        <p><strong>Kategori:</strong> {{ $upload->category }}</p>
        <p><strong>File Path:</strong> <a href="{{ $upload->file_path }}" class="text-blue-500 underline" target="_blank">Download CSV</a></p>
    </div>

    <h3 class="text-xl font-semibold mt-6">Isi Data:</h3>
    <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4 mt-4">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    @foreach ($csvData[0] as $header)
                        <th class="border border-gray-300 px-4 py-2">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach (array_slice($csvData, 1) as $row)
                    <tr class="hover:bg-gray-100">
                        @foreach ($row as $cell)
                            <td class="border border-gray-300 px-4 py-2">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
