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
                <br><span class="text-biru4 text-2xl pt-5">di Manajemen Dashboard Inflasi BPS Provinsi Jawa Timur</span>
            </h1>
            <p class="text-gray-700 text-base mt-5">
                <span class="font-bold text-biru2">Manajemen Dashboard Inflasi</span> berfungsi untuk membantu manajemen data
                inflasi secara efisien.
            </p>
            <a href="#"
                class="inline-flex items-center rounded-2xl gap-4 mt-5 pr-2 pl-4 py-1 bg-biru4 text-white font-semibold rounded-lg transition-all hover:bg-biru1 hover:translate-x-2">
                Cari tau lebih lanjut
                <img src="{{ asset('images/landingMain1/arrowRight.svg') }}" alt="panah kanan icon">
                <span class="text-lg"></span>
            </a>
        </div>
    </div>

    <!-- Ini Main 2 -->
    <div id="main2" class="min-h-[110vh] relative mb-6">
        <div class="relative w-full">
            <img src="{{ asset('images/landingMain3/batasMain3.svg') }}" alt="Pattern Batas Main 3"
                class="w-full h-fit object-cover">
        </div>

        <div class="container px-48">
            <div class="border-b-2 pb-4 border-biru5">
                <h1 class="text-3xl font-bold text-kuning1 text-start">Fitur <span class="text-biru1">yang tersedia</span>
                </h1>
            </div>

            <div class="relative mt-4 flex justify-start border-b-2 border-biru4">
                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-t-xl hover:bg-biru4 group transition duration-300"
                    data-page="tabel">
                    <img src="{{ asset('images/sidebar/bdashboardIcon.svg') }}" alt="Ikon Dashboard Inflasi"
                        class="h-6 w-6 icon group-hover:hidden transition duration-100"
                        data-hover="{{ asset('images/sidebar/pdashboardIcon.svg') }}"
                        data-default="{{ asset('images/sidebar/bdashboardIcon.svg') }}">
                    <img src="{{ asset('images/sidebar/pdashboardIcon.svg') }}" alt="Ikon Dashboard Inflasi Hover"
                        class="h-6 w-6 hidden group-hover:block transition duration-100">
                    <span
                        class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">Dashboard
                        Inflasi</span>
                </a>
                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-t-xl hover:bg-biru4 group transition duration-300"
                    data-page="tabel">
                    <img src="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}" alt="Ikon Data Inflasi"
                        class="h-6 w-6 icon group-hover:hidden transition duration-100"
                        data-hover="{{ asset('images/sidebar/pdataInflasiIcon.svg') }}"
                        data-default="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}">
                    <img src="{{ asset('images/sidebar/pdataInflasiIcon.svg') }}" alt="Ikon Data Inflasi Hover"
                        class="h-6 w-6 hidden group-hover:block transition duration-100">
                    <span
                        class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">Daftar
                        Tabel Data Inflasi</span>
                </a>
            </div>

            <p class="mt-4 text-base text-biru1 text-start">Dashboard Inflasi menyajikan data inflasi terkini di wilayah
                Provinsi Jawa Timur melalui visualisasi yang interaktif dan informatif.</p>

            <div class="flex items-start gap-6 mt-6">
                <div class="flex-row content-between">
                    <div class="bg-white shadow-xl rounded-lg p-4 mb-6">
                        <h2 class="text-lg text-biru1 font-semibold mt-2">Dashboard Inflasi Bulanan Provinsi Jawa Timur</h2>
                        <div class="flex items-center space-x-4 mt-2">
                            <img src="{{ asset('images/landingMain2/dashInflasiBulanan.svg') }}" alt="Dashboard 1"
                                class="rounded-lg w-60 h-60 object-cover">
                            <div>
                                <p class="text-base text-biru1 mt-1">Dashboard yang menyajikan hasil visualisasi data
                                    inflasi bulanan di wilayah Provinsi Jawa Timur</p>
                                <a href="#" class="text-base text-biru4 mt-2 inline-block">Lihat Selengkapnya →</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-xl rounded-lg p-4">
                        <h2 class="text-lg text-biru1 font-semibold mt-2">Dashboard Inflasi Menurut Kelompok Pengeluaran
                        </h2>
                        <div class="flex items-center space-x-4 mt-2">
                            <img src="{{ asset('images/landingMain2/dashInflasiSpasial.svg') }}" alt="Dashboard 2"
                                class="rounded-lg w-60 h-96 object-cover">
                            <div>
                                <p class="text-base text-biru1 mt-1">Dashboard yang menyajikan hasil visualisasi data
                                    inflasi bulanan menurut kelompok pengeluaran.</p>
                                <a href="#" class="text-base text-biru4 mt-2 inline-block">Lihat Selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row ">
                    <div class="bg-white shadow-xl rounded-lg p-4 mb-6">
                        <h2 class="text-lg text-biru1 font-semibold mt-2">Dashboard Inflasi Spasial Provinsi Jawa Timur</h2>
                        <div class="flex items-center space-x-4 mt-2">
                            <img src="{{ asset('images/landingMain2/dashInflasiKelompok.svg') }}" alt="Dashboard 3"
                                class="rounded-lg w-60 h-96 object-cover">
                            <div>
                                <p class="text-base text-biru1 mt-1">Dashboard yang menyajikan hasil visualisasi data
                                    inflasi bulanan di Kab/Kota IHK wilayah Provinsi Jawa Timur.</p>
                                <a href="#" class="text-base text-biru4 mt-2 inline-block">Lihat Selengkapnya →</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-xl rounded-lg p-4">
                        <h2 class="text-lg text-biru1 font-semibold mt-2">Dashboard Inflasi Series Provinsi Jawa Timur</h2>
                        <div class="flex items-center space-x-4 mt-2">
                            <img src="{{ asset('images/landingMain2/dashSeriesInflasi.svg') }}" alt="Dashboard 4"
                                class="rounded-lg w-60 h-60 object-cover">
                            <div>
                                <p class="text-base text-biru1 mt-1">Dashboard yang menyajikan hasil visualisasi data series
                                    inflasi di wilayah Provinsi Jawa Timur.</p>
                                <a href="#" class="text-base text-biru4 mt-2 inline-block">Lihat Selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
