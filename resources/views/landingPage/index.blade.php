@extends('layouts.landing')

@section('body')
    <!-- Ini Main 1 -->
    <div class="min-h-screen flex items-center justify-center relative ">
        <!-- Batik Kiri -->
        <div class="absolute top-10 left-0">
            <img src="{{ asset('images/batikMain1Atas.svg') }}" alt="Batik Left" class="h-85">
        </div>
        <!-- Batik Kanan -->
        <div class="absolute bottom-0 right-0">
            <img src="{{ asset('images/batikMain1Bawah.svg') }}" alt="Batik Right" class="h-85">
        </div>
        <!-- Konten -->
        <div class="rounded-xl border border-black p-8 max-w-2xl mx-auto flex flex-col md:flex-row items-center">
            <div class="md:w-2/3 text-center md:text-left space-y-4">
                <h1 class="text-5xl font-bold text-biru1">
                    Selamat <span class="text-biru4">Datang</span>
                    <span class="text-biru1">!</span>
                </h1>
                <p class="text-gray-600 text-base">
                    <span class="font-bold text-biru2">Dashboard Inflasi</span> menyajikan data inflasi terkini
                    di wilayah Provinsi Jawa Timur melalui visualisasi yang interaktif dan informatif
                </p>
                <a href="#"
                    class="inline-block mt-4 px-6 py-1 bg-biru4 text-white font-semibold rounded-xl hover:bg-biru1">
                    Cari tau lebih lanjut
                </a>
            </div>
            <div class="md:w-1/3 flex justify-center">
                <img src="{{ asset('images/hand.svg') }}" alt="Hand" class="h-40">
            </div>
        </div>
    </div>

    <!-- Ini Main 2 -->
    <!-- Section Dashboard -->
    <div class="min-h-screen bg-biru4 py-12 relative">
        <!-- Elemen Batik -->
        <div class="absolute top-7 left-10">
            <img src="{{ asset('images/batikKawung.svg') }}" alt="Batik Left" class="h-32">
        </div>
        <div class="absolute top-7 right-10">
            <img src="{{ asset('images/batikKawung.svg') }}" alt="Batik Right" class="h-32 transform scale-x-[-1]">
        </div>
        <div class="absolute bottom-7 left-10">
            <img src="{{ asset('images/batikKawung.svg') }}" alt="Batik Left" class="h-32 transform scale-y-[-1]">
        </div>
        <div class="absolute bottom-7 right-10">
            <img src="{{ asset('images/batikKawung.svg') }}" alt="Batik Right" class="h-32 transform scale-x-[-1] scale-y-[-1]">
        </div>
        <div class="container mx-auto text-center mt-14">
            <h2 class="text-4xl font-bold text-white">
                Yuk kenali <span class="text-kuning1">Dashboard</span> yang ada!
            </h2>
            <div class="mt-10 grid grid-cols-1 md:grid-cols-4 gap-8 px-4">
                <!-- Card 1 -->
                <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-64 -rotate-12">
                    <div class="p-4 text-center">
                        <h3 class="text-base text-biru1">
                            <span class="font-bold">Dashboard</span><br>
                            <span class="font-semibold">Inflasi Bulanan Jawa Timur</span>
                        </h3>
                    </div>
                    <img src="{{ asset('images/dashInflasiBulanan.svg') }}" alt="Dashboard Inflasi Bulanan" class="h-48 mx-auto">
                    <div class="p-4 text-left">
                        <p class="text-[0.8rem] text-gray-600">
                            Dashboard Inflasi Bulanan di Provinsi Jawa Timur.
                        </p>
                        <a href="{}" class="block mt-1 text-biru4 text-sm hover:underline">
                            Pelajari selengkapnya →
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-80 rotate-12 translate-y-24">
                    <div class="p-4 text-center">
                        <h3 class="text-base text-biru1">
                            <span class="font-bold">Dashboard</span><br>
                            <span class="font-semibold">Inflasi Spasial Jawa Timur</span>
                        </h3>
                    </div>
                    <img src="{{ asset('images/dashInflasiSpasial.svg') }}" alt="Dashboard Inflasi Spasial" class="h-80 mx-auto">
                    <div class="p-4 text-left">
                        <p class="text-[0.8rem] text-gray-600">
                            Dashboard Inflasi Bulanan di Jawa Timur yang menampilkan visualisasi berdasarkan Kab/Kota IHK.
                        </p>
                        <a href="{}" class="block mt-1 text-biru4 text-sm hover:underline">
                            Pelajari selengkapnya →
                        </a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-80 -rotate-12">
                    <div class="p-4 text-center">
                        <h3 class="text-base text-biru1">
                            <span class="font-bold">Dashboard</span><br>
                            <span class="font-semibold">Inflasi Menurut Kelompok Pengeluaran</span>
                        </h3>
                    </div>
                    <img src="{{ asset('images/dashInflasiKelompok.svg') }}" alt="Dashboard Inflasi Menurut Kelompok Pengeluaran" class="h-80 mx-auto">
                    <div class="p-4 text-left">
                        <p class="text-[0.8rem] text-gray-600">
                            Dashboard Inflasi Bulanan di Jawa Timur yang menampilkan visualisasi berdasarkan kelompok
                            pengeluaran.
                        </p>
                        <a href="#" class="block mt-1 text-biru3 text-sm hover:underline">
                            Pelajari selengkapnya →
                        </a>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-64 rotate-12 translate-y-6 </div>">
                    <div class="p-4 text-center">
                        <h3 class="text-base text-biru1">
                            <span class="font-bold">Dashboard</span><br>
                            <span class="font-semibold">Series Inflasi Jawa Timur</span>
                        </h3>
                    </div>
                    <img src="{{ asset('images/dashSeriesInflasi.svg') }}" alt="Dashboard Inflasi Bulanan" class="h-48 mx-auto">
                    <div class="p-4 text-left">
                        <p class="text-[0.8rem] text-gray-600">
                            Dashboard Series Inflasi yang menampilkan visualisasi perkembangan inflasi dari waktu ke waktu.
                        </p>
                        <a href="#" class="block mt-1 text-biru3 text-sm hover:underline">
                            Pelajari selengkapnya →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ini Main 3 -->
@endsection
