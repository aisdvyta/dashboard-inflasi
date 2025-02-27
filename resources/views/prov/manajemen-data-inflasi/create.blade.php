@extends('layouts.dashboard')

@section('body')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6 mt-10">
    <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Upload Data</h2>

    <form action="{{ route('manajemen-data-inflasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-gray-600 font-medium mb-1">Username Upload:</label>
            <input type="text" name="username" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-gray-600 font-medium mb-1">Periode Data (MM/YYYY):</label>
            <input type="text" name="period" required placeholder="MM/YYYY"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div>
            <label class="block text-gray-600 font-medium mb-1">Pilih Kategori:</label>
            <div class="space-y-2">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="category" value="ASEM 1" required class="form-radio text-blue-500">
                    <span>ASEM 1</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="category" value="ASEM 2" class="form-radio text-blue-500">
                    <span>ASEM 2</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="category" value="ASEM 3" class="form-radio text-blue-500">
                    <span>ASEM 3</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="category" value="ATAP" class="form-radio text-blue-500">
                    <span>ATAP</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-gray-600 font-medium mb-1">Upload Data (XLSX):</label>
            <input type="file" name="file" accept=".xlsx" required
                class="w-full px-4 py-2 border rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <button type="submit"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
            Submit
        </button>
    </form>
</div>
@endsection
