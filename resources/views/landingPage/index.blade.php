@extends('layouts.landing')

@section('body')
    <!-- Ini Main 1 -->
    <div id="main1" class="min-h-screen flex items-center justify-center relative ">
        <!-- Batik Kiri -->
        <div class="absolute top-10 left-0">
            <img src="{{ asset('images/batikMain1Atas.svg') }}" alt="Batik Left" class="h-85">
        </div>
        <!-- Batik Kanan -->
        <div class="absolute bottom-0 right-0">
            <img src="{{ asset('images/batikMain1Bawah.svg') }}" alt="Batik Right" class="h-85">
        </div>
        <!-- Konten -->
        <div class="rounded-xl p-8 max-w-2xl mx-auto flex flex-col md:flex-row items-center">
            <div class="md:w-2/3 text-center md:text-left space-y-4">
                <h1 class="text-5xl font-bold text-biru1">
                    Selamat <span class="text-biru4">Datang</span>
                    <span class="text-biru1">!</span>
                </h1>
                <p class="text-gray-600 text-base">
                    <span class="font-bold text-biru2">Dashboard Inflasi</span> menyajikan data inflasi terkini di wilayah Provinsi Jawa Timur melalui visualisasi yang interaktif dan informatif
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
    @include('components.dashboardcard')

    <!-- Ini Main 3 -->
    <div id="main3" class="min-h-[93vh]">
        <!-- Banner Pattern -->
        <div class="relative w-full">
            <img src="{{ asset('images/batasMain3.svg') }}" alt="Pattern Batas Main 3" class="w-full h-fit object-cover">
        </div>

        <div class="container mx-auto px-6 md:px-12 lg:px-24 flex flex-col md:flex-row items-center mt-8">
            <!-- Image -->
            <div class="w-full md:w-1/3 flex flex-col items-center -translate-y-8">
                <img src="{{ asset('images/pakZulKartun.svg') }}" alt="Kepala BPS Provinsi Jawa Timur" class="w-80 h-auto object-cover">
                <p class="mt-2 text-biru1 text-center font-semibold">Bapak Dr. Zulkipli, M.Si.</p>
                <p class="text-biru1 text-center">Kepala BPS Provinsi Jawa Timur</p>
            </div>

            <!-- Text -->
            <div class="w-full md:w-2/3 md:pl-12">
                <h2 class="text-3xl font-bold text-biru1">
                    <span class="text-kuning1">Tentang</span> Kami
                </h2>
                <p class="mt-4 text-biru1 text-justify">
                    <span class="font-bold">Dashboard Inflasi</span> adalah sistem informasi yang dikembangkan oleh BPS Provinsi Jawa Timur untuk menyajikan data inflasi terkini, baik di tingkat provinsi maupun kabupaten/kota yang masuk dalam cakupan IHK wilayah Jawa Timur. Data ini disajikan melalui visualisasi yang interaktif dan informatif, sehingga memudahkan pengguna dalam memahami perkembangan inflasi.
                </p>
                <p class="mt-4 text-biru1 text-justify">
                    Dashboard ini, terdiri atas Dashboard Inflasi Bulanan Provinsi Jawa Timur, Dashboard Inflasi Spasial, Dashboard Inflasi Menurut Kelompok Pengeluaran, dan Dashboard Series Inflasi. Dengan hadirnya sistem ini, efisiensi dan akurasi dalam pengelolaan data inflasi di wilayah Provinsi Jawa Timur diharapkan dapat meningkat secara signifikan.
                </p>
            </div>
        </div>
    </div>

    <!-- Ini Main 4 -->
    <div id="main4" class="bg-white py-16 px-5 min-h-[93vh]">
        <div class="max-w-7xl mx-auto h-fit bg-gray-100 rounded-3xl shadow-lg p-8 relative">
            <!-- Elemen Batik -->
            <div class="absolute top-10 left-10">
                <img src="{{ asset('images/batikKawung2.webp') }}" alt="Batik Left" class="h-32">
            </div>
            <div class="absolute top-10 right-10">
                <img src="{{ asset('images/batikKawung2.webp') }}" alt="Batik Right" class="h-32 transform scale-x-[-1]">
            </div>
            <div class="absolute bottom-10 left-10">
                <img src="{{ asset('images/batikKawung2.webp') }}" alt="Batik Left" class="h-32 transform scale-y-[-1]">
            </div>
            <div class="absolute bottom-10 right-10">
                <img src="{{ asset('images/batikKawung2.webp') }}" alt="Batik Right" class="h-32 transform scale-x-[-1] scale-y-[-1]">
            </div>

            <!-- Judul -->
            <h2 class="text-center text-3xl font-bold text-biru1">
                Ikuti <span class="text-kuning1">jejak sosial</span> kami
            </h2>

            <!-- Grid Social Media -->
            <div class="grid grid-cols-3 gap-8 mx-60 mt-10 mb-5 ">
                <!-- Instagram -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/instagramIcon.svg') }}" alt="Instagram Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">Instagram</h3>
                    <a href="https://www.instagram.com/bpsjatim?igsh=MWE1cHA1NG9rdWhpbg==" target="_blank" class="text-biru4 text-sm font-normal hover:underline">Lihat Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/kontIGKiri.svg') }}" class="rounded-xl translate-x-4 translate-y-2">
                        <img src="{{ asset('images/kontIGMid.svg') }}" class="rounded-xl z-10 -translate-x-1">
                        <img src="{{ asset('images/kontIGKanan.svg') }}" class="rounded-xl -translate-x-5 translate-y-2">
                    </div>
                </div>

                <!-- Website -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/websiteIcon.svg') }}" alt="Website Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">Website</h3>
                    <a href="https://jatim.bps.go.id/id" target="_blank" class="text-biru4 text-sm font-normal hover:underline">Lihat Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/kontWebsite.svg') }}" class="mt-2 rounded-lg w-full translate-y-2">

                    </div>
                </div>


                <!-- X (Twitter) -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/xIcon.svg') }}" alt="X Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">Twitter</h3>
                    <a href="https://x.com/bpsjatim" target="_blank" class="text-biru4 text-sm font-normal hover:underline">Lihat Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/kontXKiri.svg') }}" class="rounded-xl translate-x-5 translate-y-2">
                        <img src="{{ asset('images/kontXMid.svg') }}" class="rounded-xl z-10 -translate-x-1">
                        <img src="{{ asset('images/kontXKanan.svg') }}" class="rounded-xl -translate-x-6 translate-y-2">
                    </div>
                </div>

                <!-- Facebook -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/facebookIcon.svg') }}" alt="Facebook Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">Facebook</h3>
                    <a href="https://www.facebook.com/bpsjatim" target="_blank" class="text-biru4 text-sm font-normal hover:underline">Lihat Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/kontFBKiri.svg') }}" class="rounded-xl translate-x-5 translate-y-3">
                        <img src="{{ asset('images/kontFBMid.svg') }}" class="rounded-xl z-10 -translate-x-1 translate-y-1">
                        <img src="{{ asset('images/kontFBKanan.svg') }}" class="rounded-xl -translate-x-6 translate-y-3">
                    </div>
                </div>

                <!-- YouTube -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/youtubeIcon.svg') }}" alt="YouTube Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">YouTube</h3>
                    <a href="https://www.youtube.com/@bpsprovjatim" target="_blank" class="text-biru4 text-sm font-normal hover:underline">Lihat Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/kontYtKiri.svg') }}" class="rounded-xl translate-x-5 translate-y-3">
                        <img src="{{ asset('images/kontYtKanan.svg') }}" class="rounded-xl -translate-x-6 -translate-y-3">
                    </div>
                </div>

                <!-- TikTok -->
                <div class="h-[13rem] w-[12rem] bg-white p-4 rounded-3xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/tiktokIcon.svg') }}" alt="TikTok Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-bold text-biru1">TikTok</h3>
                    <a href="https://www.tiktok.com/@bpsjatim" target="_blank" class="text-biru4 text-sm font-normal hover:underline">Lihat Profil →</a>
                    <div class="mt-4 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/kontTtKiri.svg') }}" class="rounded-xl translate-x-5 translate-y-2">
                        <img src="{{ asset('images/kontTtMid.svg') }}" class="rounded-xl z-10 -translate-x-1">
                        <img src="{{ asset('images/kontTtKanan.svg') }}" class="rounded-xl -translate-x-6 translate-y-2">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("a[href='#']").addEventListener("click", function (event) {
        event.preventDefault();

        let target = document.getElementById("main2");
        if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    });
});

</script>
