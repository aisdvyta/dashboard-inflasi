@extends('layouts.landing')

@section('body')
    <!-- Ini Main 1 -->
    <div id="main1" class="relative min-h-screen flex items-center justify-center bg-cover bg-center bg-gradient-mask"
        style="background-image: url('{{ asset('images/landingMain1/backLandingPage.svg') }}');">

        <!-- Konten -->
        <div
            class="relative rounded-[3rem] p-8 max-w-2xl mx-auto flex flex-col items-center text-center bg-white bg-opacity-65 shadow-xl backdrop-blur-md md:p-10 md:items-start md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-biru1">
                Selamat Datang
                <br><span class="text-biru1">di</span>
                <span class="text-kuning1"> Dashboard Inflasi</span>
            </h1>
            <p class="text-gray-700 text-base mt-5">
                <span class="font-bold text-biru2">Dashboard Inflasi</span> menyajikan data inflasi terkini di wilayah
                Provinsi Jawa Timur melalui visualisasi yang interaktif dan informatif.
            </p>
            <a href="#"
                class="inline-flex items-center rounded-2xl gap-4 mt-5 pr-2 pl-4 py-1 bg-biru4 text-white font-semibold rounded-lg transition-all duration-300 hover:bg-biru1 hover:translate-x-2">
                Cari tau lebih lanjut
                <img src="{{ asset('images/landingMain1/arrowRight.svg') }}" alt="panah kanan icon">
                <span class="text-lg"></span>
            </a>
        </div>
    </div>

    <!-- Ini Main 2 -->
    <div id="main3" class="relative min-h-[70vh] pt-18 flex items-center justify-center overflow-hidden">
        <!-- Batik Kawung Kiri Atas -->
        <img src="{{ asset('images/landingMain4/batikKawung2.svg') }}" alt="Batik Kawung Kiri"
            class="absolute top-10 left-12 h-28">

        <!-- Batik Kawung Kanan Atas -->
        <img src="{{ asset('images/landingMain4/batikKawung2.svg') }}" alt="Batik Kawung Kanan"
            class="absolute transform scale-x-[-1] top-10 right-12 h-28">

        <!-- Konten -->
        <div class="container relative z-10 mx-auto px-6 md:px-12 lg:px-24">
            <div class="w-full md:w-2/3 mx-auto text-center">
                <h2 class="text-3xl font-bold text-biru1">
                    <span class="text-kuning1">Tentang</span> Kami
                </h2>

                <p class="mt-8 text-biru1 text-justify indent-8">
                    <span class="font-bold">Dashboard Inflasi</span> adalah sistem informasi yang dikembangkan oleh BPS
                    Provinsi Jawa Timur untuk menyajikan data inflasi terkini, baik di tingkat provinsi maupun
                    kabupaten/kota yang masuk dalam cakupan IHK wilayah Jawa Timur. Data ini disajikan melalui visualisasi
                    yang interaktif dan informatif, sehingga memudahkan pengguna dalam memahami perkembangan inflasi.
                    Dashboard ini terdiri atas Dashboard Inflasi Bulanan Provinsi Jawa Timur,
                    Dashboard Inflasi Menurut Kelompok Pengeluaran, dan Dashboard Series Inflasi.
                </p>
            </div>
        </div>
    </div>


    <!-- Ini Main 3 -->
    <!-- Section Dashboard -->
    <div>
        @include('components.dashboardcard')
    </div>

    <!-- Ini Main 4 -->
    <div id="main4" class="bg-white py-16 px-5 min-h-[93vh]">
        <div class="max-w-7xl mx-auto h-fit bg-gray-100 rounded-3xl shadow-lg p-8 relative">
            <!-- Elemen Batik -->
            <div class="absolute top-10 left-10">
                <img src="{{ asset('images/landingMain4/batikKawung2.svg') }}" alt="Batik Left" class="h-28">
            </div>
            <div class="absolute top-10 right-10">
                <img src="{{ asset('images/landingMain4/batikKawung2.svg') }}" alt="Batik Right"
                    class="h-28 transform scale-x-[-1]">
            </div>
            <div class="absolute bottom-10 left-10">
                <img src="{{ asset('images/landingMain4/batikKawung2.svg') }}" alt="Batik Left"
                    class="h-28 transform scale-y-[-1]">
            </div>
            <div class="absolute bottom-10 right-10">
                <img src="{{ asset('images/landingMain4/batikKawung2.svg') }}" alt="Batik Right"
                    class="h-28 transform scale-x-[-1] scale-y-[-1]">
            </div>

            <!-- Judul -->
            <h2 class="text-center text-3xl font-bold text-biru1">
                Ikuti <span class="text-kuning1">jejak sosial</span> kami
            </h2>

            <!-- Grid Social Media -->
            <div class="grid grid-cols-3 gap-8 mx-60 mt-10 mb-5 ">
                <!-- Instagram -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden ">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/landingMain4/instagramIcon.svg') }}" alt="Instagram Logo"
                            class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">Instagram</h3>
                    <a href="https://www.instagram.com/bpsjatim?igsh=MWE1cHA1NG9rdWhpbg==" target="_blank"
                        class="text-biru4 text-sm font-normal transition-transform duration-200 hover:text-biru2 hover:translate-x-2 inline-block">
                        Lihat Profil →
                    </a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/landingMain4/kontIGKiri.svg') }}"
                            class="rounded-xl translate-x-4 translate-y-2">
                        <img src="{{ asset('images/landingMain4/kontIGMid.svg') }}" class="rounded-xl z-10 -translate-x-1">
                        <img src="{{ asset('images/landingMain4/kontIGKanan.svg') }}"
                            class="rounded-xl -translate-x-5 translate-y-2">
                    </div>
                </div>

                <!-- Website -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/landingMain4/websiteIcon.svg') }}" alt="Website Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">Website</h3>
                    <a href="https://jatim.bps.go.id/id" target="_blank"
                        class="text-biru4 text-sm font-normal transition-all hover:text-biru2 hover:translate-x-2 inline-block">Lihat
                        Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/landingMain4/kontWebsite.svg') }}"
                            class="mt-2 rounded-lg w-full translate-y-2">

                    </div>
                </div>


                <!-- X (Twitter) -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/landingMain4/xIcon.svg') }}" alt="X Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">Twitter</h3>
                    <a href="https://x.com/bpsjatim" target="_blank"
                        class="text-biru4 text-sm font-normal transition-all hover:text-biru2 hover:translate-x-2 inline-block">Lihat
                        Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/landingMain4/kontXKiri.svg') }}"
                            class="rounded-xl translate-x-5 translate-y-2">
                        <img src="{{ asset('images/landingMain4/kontXMid.svg') }}" class="rounded-xl z-10 -translate-x-1">
                        <img src="{{ asset('images/landingMain4/kontXKanan.svg') }}"
                            class="rounded-xl -translate-x-6 translate-y-2">
                    </div>
                </div>

                <!-- Facebook -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/landingMain4/facebookIcon.svg') }}" alt="Facebook Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">Facebook</h3>
                    <a href="https://www.facebook.com/bpsjatim" target="_blank"
                        class="text-biru4 text-sm font-normal transition-all hover:text-biru2 hover:translate-x-2 inline-block">Lihat
                        Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/landingMain4/kontFBKiri.svg') }}"
                            class="rounded-xl translate-x-5 translate-y-3">
                        <img src="{{ asset('images/landingMain4/kontFBMid.svg') }}"
                            class="rounded-xl z-10 -translate-x-1 translate-y-1">
                        <img src="{{ asset('images/landingMain4/kontFBKanan.svg') }}"
                            class="rounded-xl -translate-x-6 translate-y-3">
                    </div>
                </div>

                <!-- YouTube -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/landingMain4/youtubeIcon.svg') }}" alt="YouTube Logo"
                            class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">YouTube</h3>
                    <a href="https://www.youtube.com/@bpsprovjatim" target="_blank"
                        class="text-biru4 text-sm font-normal transition-all hover:text-biru2 hover:translate-x-2 inline-block">Lihat
                        Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/landingMain4/kontYtKiri.svg') }}"
                            class="rounded-xl translate-x-5 translate-y-3">
                        <img src="{{ asset('images/landingMain4/kontYtKanan.svg') }}"
                            class="rounded-xl -translate-x-6 -translate-y-3">
                    </div>
                </div>

                <!-- TikTok -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/landingMain4/tiktokIcon.svg') }}" alt="TikTok Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">TikTok</h3>
                    <a href="https://www.tiktok.com/@bpsjatim" target="_blank"
                        class="text-biru4 text-sm font-normal transition-all hover:text-biru2 hover:translate-x-2 inline-block">Lihat
                        Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/landingMain4/kontTtKiri.svg') }}"
                            class="rounded-xl translate-x-5 translate-y-2">
                        <img src="{{ asset('images/landingMain4/kontTtMid.svg') }}"
                            class="rounded-xl z-10 -translate-x-1">
                        <img src="{{ asset('images/landingMain4/kontTtKanan.svg') }}"
                            class="rounded-xl -translate-x-6 translate-y-2">
                    </div>
                </div>
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
