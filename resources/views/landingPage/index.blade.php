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
        <div class="rounded-xl p-8 max-w-2xl mx-auto flex flex-col md:flex-row items-center">
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
    <div class="min-h-[110vh] bg-biru4 py-12 relative">
        <!-- Elemen Batik -->
        <div class="absolute top-20 left-10">
            <img src="{{ asset('images/batikKawung.svg') }}" alt="Batik Left" class="h-32">
        </div>
        <div class="absolute top-20 right-10">
            <img src="{{ asset('images/batikKawung.svg') }}" alt="Batik Right" class="h-32 transform scale-x-[-1]">
        </div>
        <div class="absolute bottom-8 left-10">
            <img src="{{ asset('images/batikKawung.svg') }}" alt="Batik Left" class="h-32 transform scale-y-[-1]">
        </div>
        <div class="absolute bottom-8 right-10">
            <img src="{{ asset('images/batikKawung.svg') }}" alt="Batik Right" class="h-32 transform scale-x-[-1] scale-y-[-1]">
        </div>

        <div class="container mx-auto text-center mt-8">
            <h2 class="text-4xl font-bold text-white">
                Yuk kenali <span class="text-kuning1">Dashboard</span> yang ada!
            </h2>
            <div class="mt-10 grid grid-cols-1 md:grid-cols-4 gap-8 px-48">
                <!-- Card 1 -->
                <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-64 -rotate-10 translate-x-4 translate-y-2">
                    <div class="p-3 text-center">
                        <h3 class="text-sm text-biru1">
                            <span class="font-bold">Dashboard</span><br>
                            <span class="font-semibold">Inflasi Bulanan Jawa Timur</span>
                        </h3>
                    </div>
                    <img src="{{ asset('images/dashInflasiBulanan.svg') }}" alt="Dashboard Inflasi Bulanan" class="h-48 mx-auto">
                    <div class="p-3 text-left">
                        <p class="text-[0.8rem] text-gray-600">
                            Dashboard Inflasi Bulanan di Provinsi Jawa Timur.
                        </p>
                        <a href="{}" class="block mt-1 text-biru4 text-sm hover:underline">
                            Pelajari selengkapnya →
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-[18rem] rotate-10 -translate-x-2 translate-y-10">
                    <div class="p-3 text-center">
                        <h3 class="text-sm text-biru1">
                            <span class="font-bold">Dashboard</span><br>
                            <span class="font-semibold">Inflasi Spasial Jawa Timur</span>
                        </h3>
                    </div>
                    <img src="{{ asset('images/dashInflasiSpasial.svg') }}" alt="Dashboard Inflasi Spasial" class="h-80 mx-auto">
                    <div class="p-3 text-left">
                        <p class="text-[0.8rem] text-gray-600">
                            Dashboard Inflasi Bulanan di Jawa Timur yang menampilkan visualisasi berdasarkan Kab/Kota IHK.
                        </p>
                        <a href="{}" class="block mt-1 text-biru4 text-sm hover:underline">
                            Pelajari selengkapnya →
                        </a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-[18rem] -rotate-10 translate-y-2">
                    <div class="p-3 text-center">
                        <h3 class="text-sm text-biru1">
                            <span class="font-bold">Dashboard</span><br>
                            <span class="font-semibold">Inflasi Menurut Kelompok Pengeluaran</span>
                        </h3>
                    </div>
                    <img src="{{ asset('images/dashInflasiKelompok.svg') }}" alt="Dashboard Inflasi Menurut Kelompok Pengeluaran" class="h-80 mx-auto">
                    <div class="p-3 text-left">
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
                <div class="bg-white rounded-3xl overflow-hidden h-fit w-64 rotate-10 translate-x-2 translate-y-10">
                    <div class="p-3 text-center">
                        <h3 class="text-sm text-biru1">
                            <span class="font-bold">Dashboard</span><br>
                            <span class="font-semibold">Series Inflasi Jawa Timur</span>
                        </h3>
                    </div>
                    <img src="{{ asset('images/dashSeriesInflasi.svg') }}" alt="Dashboard Inflasi Bulanan" class="h-48 mx-auto">
                    <div class="p-3 text-left">
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
    <div class="bg-gray-100  shadow-xl min-h-[93vh]">
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
    <div class="bg-white py-16 px-5 min-h-[93vh]">
        <div class="max-w-7xl mx-auto h-fit bg-gray-100 rounded-3xl shadow-lg p-8 relative">
            <!-- Elemen Batik -->
            <div class="absolute top-10 left-10">
                <img src="{{ asset('images/batikKawung2.svg') }}" alt="Batik Left" class="h-32">
            </div>
            <div class="absolute top-10 right-10">
                <img src="{{ asset('images/batikKawung2.svg') }}" alt="Batik Right" class="h-32 transform scale-x-[-1]">
            </div>
            <div class="absolute bottom-10 left-10">
                <img src="{{ asset('images/batikKawung2.svg') }}" alt="Batik Left" class="h-32 transform scale-y-[-1]">
            </div>
            <div class="absolute bottom-10 right-10">
                <img src="{{ asset('images/batikKawung2.svg') }}" alt="Batik Right" class="h-32 transform scale-x-[-1] scale-y-[-1]">
            </div>

            <!-- Judul -->
            <h2 class="text-center text-3xl font-bold text-biru1">
                Ikuti <span class="text-kuning1">jejak sosial</span> kami
            </h2>

            <!-- Grid Social Media -->
            <div class="grid grid-cols-3 gap-20 mx-60 mt-8 mb-5 ">
                <!-- Instagram -->
                <div class="bg-white p-4 rounded-xl shadow-lg text-center overflow-hidden">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/instagramIcon.svg') }}" alt="Instagram Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-semibold text-biru1">Instagram</h3>
                    <a href="https://www.instagram.com/bpsjatim?igsh=MWE1cHA1NG9rdWhpbg==" target="_blank" class="text-biru4 font-normal block mt-2 hover:underline">Lihat Profil →</a>
                    <div class="mt-2 flex space-x-0 items-center justify-center">
                        <img src="{{ asset('images/kontIGKiri.svg') }}" class="rounded-lg translate-x-5 ">
                        <img src="{{ asset('images/kontIGMid.svg') }}" class="rounded-lg z-10>
                        <img src="{{ asset('images/kontIGKanan.svg') }}" class="rounded-lg -translate-x-5 ">
                    </div>
                </div>

                <!-- Website -->
                <div class="bg-white p-4 rounded-xl shadow-lg text-center">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/websiteIcon.svg') }}" alt="Website Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-semibold text-biru1">Website</h3>
                    <a href="#" class="text-biru4 font-normal block mt-2 hover:underline">Lihat Profil →</a>
                    <img src="{{ asset('images/website-preview.png') }}" class="mt-2 rounded-lg">
                </div>

                <!-- X (Twitter) -->
                <div class="bg-white p-4 rounded-xl shadow-lg text-center">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/xIcon.svg') }}" alt="X Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-semibold text-biru1">Twitter</h3>
                    <a href="#" class="text-biru4 font-normal block mt-2 hover:underline">Lihat Profil →</a>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <img src="{{ asset('images/twitter-post1.jpg') }}" class="rounded-lg">
                        <img src="{{ asset('images/twitter-post2.jpg') }}" class="rounded-lg">
                    </div>
                </div>

                <!-- Facebook -->
                <div class="bg-white p-4 rounded-xl shadow-lg text-center">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/facebookIcon.svg') }}" alt="Facebook Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-semibold text-biru1">Facebook</h3>
                    <a href="#" class="text-biru4 font-normal block mt-2 hover:underline">Lihat Profil →</a>
                    <img src="{{ asset('images/facebook-preview.png') }}" class="mt-2 rounded-lg">
                </div>

                <!-- YouTube -->
                <div class="bg-white p-4 rounded-xl shadow-lg text-center">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/youtubeIcon.svg') }}" alt="YouTube Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-semibold text-biru1">YouTube</h3>
                    <a href="#" class="text-biru4 font-normal block mt-2 hover:underline">Lihat Profil →</a>
                    <img src="{{ asset('images/youtube-preview.png') }}" class="mt-2 rounded-lg">
                </div>

                <!-- TikTok -->
                <div class="bg-white p-4 rounded-xl shadow-lg text-center">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/tiktokIcon.svg') }}" alt="TikTok Logo" class="w-8 h-8">
                    </div>
                    <h3 class="mt-4 font-semibold text-biru1">TikTok</h3>
                    <a href="#" class="text-biru4 font-normal block mt-2 hover:underline">Lihat Profil →</a>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <img src="{{ asset('images/tiktok-post1.jpg') }}" class="rounded-lg">
                        <img src="{{ asset('images/tiktok-post2.jpg') }}" class="rounded-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
