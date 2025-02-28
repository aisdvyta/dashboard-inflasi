@extends('layouts.landingProvKab')

@section('body')
    <!-- Ini Main 1 -->
    <div id="main1" class="relative min-h-screen flex items-center justify-center bg-cover bg-center"
        style="background-image: url('{{ asset('images/backMain1.svg') }}');">

        <!-- Konten -->
        <div
            class="relative rounded-[3rem] p-8 max-w-3xl mx-auto flex flex-col items-center text-center bg-white bg-opacity-65 shadow-xl backdrop-blur-md md:p-10 md:items-start md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-biru1">
                Selamat Datang,
                <span class="text-kuning1">Admin</span>
                <br><span class="text-biru4 text-2xl pt-5">di Manajemen Dashboard Inflasi BPS Provinsi Jawa Timur</span>

            </h1>
            <p class="text-gray-700 text-base mt-5">
                <span class="font-bold text-biru2">Manajemen Dashboard Inflasi</span> berfungsi untuk membantu manajemen data inflasi secara efisien.
            </p>
            <a href="#"
                class="inline-flex items-center rounded-2xl gap-4 mt-5 pr-2 pl-4 py-1 bg-biru4 text-white font-semibold rounded-lg transition-all hover:bg-biru1 hover:translate-x-1">
                Cari tau lebih lanjut
                <img src="{{ asset('images/landingMain1/arrowRight.svg') }}" alt="panah kanan icon">
                <span class="text-lg"></span>
            </a>
        </div>
    </div>

    <!-- Ini Main 2 -->
    <div id="main2" class="min-h-[110vh] bg-biru4 py-12 relative">
        <!-- Elemen Batik -->
        <div class="absolute pt-8 top-30 left-10 ">
            <img src="{{ asset('images/landingMain2/batikKawung.svg') }}" alt="Batik Left" class="h-32 ">
        </div>
        <div class="absolute pt-8 top-30 right-10">
            <img src="{{ asset('images/landingMain2/batikKawung.svg') }}" alt="Batik Right"
                class="h-32 transform scale-x-[-1]">
        </div>

        <div class="container mx-auto text-center mt-8">
            <h2 class="text-4xl font-bold text-white">
                Menu <span class="text-kuning1">Manajemen Dashboard</span> yang tersedia!
            </h2>
            <p class="text-white text-base font-normal mt-8 mx-80">
                <span class="font-semibold text-kuning2">Manajemen Dashboard</span> yang tersedia mencakup pengaturan akun BPS kabupaten/kota,
                penginputan dan pembaruan data inflasi, hingga penyajian hasil visualisasi dalam bentuk grafik dan tabel interaktif.
            </p>
        </div>
    </div>
@endsection
