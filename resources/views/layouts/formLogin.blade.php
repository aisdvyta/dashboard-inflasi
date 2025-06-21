@extends('layouts.daleman')

@section('body')
    <div class="relative min-h-[95vh] w-full flex flex-col gap-5 items-center justify-center bg-abubiru overflow-hidden">
        <!-- Background Image Kiri -->
        <div class="absolute left-0 top-0 w-3/5 h-full">
            <img src="{{ asset('images/login/bgSurabaya.svg') }}" alt="Background Monumen Surabaya"
                class="w-full h-full object-cover bg-gradient-mask-right">
        </div>
        <!-- Batik Kawung di kiri atas -->
        <div class="absolute top-10 right-52 -translate-x-80 -translate-y-24">
            <img src="{{ asset('images/kawung.svg') }}" alt="Batik Kawung" class="h-72 -rotate-[5deg]">
        </div>

        <!-- Batik Kawung di kanan bawah (diperbaiki) -->
        <div class="absolute bottom-12 right-6 translate-x-20 translate-y-10">
            <img src="{{ asset('images/kawung.svg') }}" alt="Batik Kawung" class="h-64 rotate-[30deg]">
        </div>

        <!-- Konten -->
        <div class="translate-x-72 translate-y-10">
            <h2 class="text-4xl font-bold text-biru1 pb-6">Silahkan <span class="text-kuning1">Login</span> Terlebih Dahulu!
            </h2>

            <div class="w-full max-w-md bg-white px-4 pt-4 pb-1 rounded-lg shadow-md text-center">
                @if ($errors->has('login'))
                    <div class="font-semibold mb-4 text-merah2">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-4 text-left">
                        <label for="nama" class="block text-biru1 font-semibold">Username/Email</label>
                        <input type="text" id="nama" name="nama" required
                            class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5">
                    </div>

                    <div class="mb-6 text-left relative">
                        <label for="password" class="block text-biru1 font-semibold">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full mt-1 p-2 rounded-2xl border border-biru5 focus:ring-biru5 pr-10">

                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-9">
                            <img src="{{ asset('images/login/eyeOffIcon.svg') }}" id="eyeToggle" class="w-5 h-5">
                        </button>
                    </div>

                    <div class="flex justify-center mb-2">
                        <button type="submit"
                            class="w-full bg-biru4 rounded-lg text-white font-semibold py-2 hover:bg-biru5 hover:text-biru1 transition">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('eyeToggle'); // Pastikan ID sesuai dengan elemen gambar
            if (passwordInput.type === 'text') {
                passwordInput.type = 'password';
                toggleIcon.src = "{{ asset('images/login/eyeOffIcon.svg') }}"; // Ganti ikon menjadi "eyeOffIcon"
            } else {
                passwordInput.type = 'text';
                toggleIcon.src = "{{ asset('images/login/eyeIcon.svg') }}"; // Ganti ikon menjadi "eyeIcon"
            }
        }
    </script>
