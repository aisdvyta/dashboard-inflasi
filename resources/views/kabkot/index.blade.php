@extends('layouts.landingProvKab')

@section('body')
    <!-- Ini Main 1 -->
    <div id="main1" class="relative min-h-screen flex items-center justify-center bg-cover bg-center bg-gradient-mask"
        style="background-image: url('{{ asset('images/backMain1.svg') }}');">

        <!-- Konten -->
        <div
            class="relative rounded-[3rem] p-8 max-w-3xl mx-auto flex flex-col items-center text-center bg-white bg-opacity-65 shadow-xl backdrop-blur-md md:p-10 md:items-start md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-biru1">
                Selamat Datang
                <br><span class="text-biru4 text-2xl pt-5">di Manajemen Dashboard Inflasi BPS Kab/Kot Jawa Timur</span>
            </h1>
            <p class="text-gray-700 text-base mt-5">
                <span class="font-bold text-biru2">Manajemen Dashboard Inflasi</span> berfungsi untuk membantu manajemen data
                inflasi secara efisien.
            </p>
            <a href="#"
                class="inline-flex items-center rounded-2xl gap-4 mt-5 pr-2 pl-4 py-1 bg-biru4 text-white font-semibold  transition-all hover:bg-biru1 hover:translate-x-2">
                Cari tau lebih lanjut
                <img src="{{ asset('images/landingMain1/arrowRight.svg') }}" alt="panah kanan icon">
                <span class="text-lg"></span>
            </a>
        </div>
    </div>

    <!-- Ini Main 2 -->
    <div id="main2" class="min-h-[110vh] bg-white py-12 relative">
        <!-- Elemen Batik -->
        <div class="absolute pt-10 top-30 left-10 ">
            <img src="{{ asset('images/landingMain4/batikKawung2.svg') }}" alt="Batik Left" class="h-28 ">
        </div>
        <div class="absolute pt-10 top-30 right-10">
            <img src="{{ asset('images/landingMain4/batikKawung2.svg') }}" alt="Batik Right"
                class="h-28 transform scale-x-[-1]">
        </div>

        <div class="container mx-auto text-center mt-14">
            <h2 class="text-4xl font-bold text-biru1">
                Menu <span class="text-kuning1">Manajemen Dashboard</span> yang tersedia!
            </h2>
            <p class="text-biru1 text-base font-normal mt-10 mx-80">
                <span class="font-semibold">Manajemen Dashboard</span> yang tersedia mencakup pengaturan akun
                BPS kabupaten/kota,
                penginputan dan pembaruan data inflasi, hingga penyajian hasil visualisasi dalam bentuk grafik dan tabel
                interaktif.
            </p>
        </div>

        <div class="flex flex-wrap justify-center gap-12 mt-24">

            <!-- Manajemen Data Inflasi -->
            <div class="bg-white p-6 rounded-2xl shadow-lg w-80 flex flex-col items-stretch text-start">
                <img src="{{ asset('images/adminProv/manajemenDataInflasi.svg') }}" alt="Manajemen Data Inflasi"
                    class="self-center w-40 h-60 mb-4">
                <h2 class="text-biru1 text-lg font-semibold">Manajemen Data Inflasi</h2>
                <p class="text-biru1 text-sm mt-2 flex-grow">Melakukan penambahan, pengeditan, atau penghapusan data inflasi
                    sementara (ASEM) atau tetap (ATAP)</p>
                <a href="{{ route('manajemen-data-inflasi.index') }}"
                    class="text-biru4 mt-3 transition-transform duration-200 hover:translate-x-2 inline-block">Lakukan →</a>
            </div>

            <!-- Dashboard Inflasi -->
            <div class="bg-white p-6 rounded-2xl shadow-lg w-80 flex flex-col items-stretch text-start">
                <img src="{{ asset('images/adminProv/dashInflasi.svg') }}" alt="Dashboard Inflasi"
                    class="self-center w-40 h-60 mb-4">
                <h2 class="text-biru1 text-lg font-semibold">Dashboard Inflasi</h2>
                <p class="text-biru1 text-sm mt-2 flex-grow">Melihat tampilan visualisasi data inflasi di dashboard Provinsi
                    Jawa Timur</p>
                <a href="{{ route('dashboard.spasial') }}"
                    class="text-biru4 mt-3 transition-transform duration-200 hover:translate-x-2 inline-block">Lakukan →</a>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector("a[href='#']").addEventListener("click", function(event) {
            event.preventDefault();

            let target = document.getElementById("main2");
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
            }
        });
    });
</script>

