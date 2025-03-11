@extends('layouts.daleman')

@section('body')

<div class="relative min-h-[95vh] w-full flex flex-col gap-5 items-center justify-center bg-abubiru overflow-hidden">

    <!-- Batik Kawung di kiri atas -->
    <div class="absolute top-10 left-0 -translate-x-20">
        <img src="{{ asset('images/kawung.svg') }}" alt="Batik Kawung" class="h-80 -rotate-[5deg]">
    </div>

    <!-- Batik Kawung di kanan bawah (diperbaiki) -->
    <div class="absolute bottom-10 right-0 translate-x-20 ">
        <img src="{{ asset('images/kawung.svg') }}" alt="Batik Kawung" class="h-80 rotate-[30deg]">
    </div>

    <!-- Konten -->
    <h2 class="text-4xl font-bold text-biru1">Silahkan <span class="text-kuning1">Login</span> Terlebih Dahulu!</h2>

    <div class="w-full max-w-sm bg-white p-4 rounded-lg shadow-md text-center">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-4 text-left">
                <label for="username" class="block text-biru1 font-normal">Username</label>
                <input type="text" id="username" name="username" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring focus:ring-biru5">
            </div>

            <div class="mb-6 text-left relative">
                <label for="password" class="block text-biru1 font-normal">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring focus:ring-biru5">
            </div>

            <button type="submit"
                class="w-full bg-biru1 rounded-2xl text-white font-semibold py-2 hover:bg-biru4 transition">Login</button>
        </form>
    </div>
</div>

@endsection
