@extends('layouts.daleman')

@section('body')
    <div id="main1" class="relative h-screen">
        @include('components.modaTambahSatker', ['fileName' => 'cobabuat-moda'])
        <button onclick="document.getElementById('modalTambahSatker').classList.remove('hidden')"
            class="bg-biru1 text-white px-4 py-2 rounded-lg">
            Buka Modal
        </button>
    </div>
@endsection
