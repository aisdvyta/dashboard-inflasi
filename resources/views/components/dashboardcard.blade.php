<div id="main2" class="min-h-[110vh] bg-biru4 py-12 relative">
    <!-- Elemen Batik -->
    <div class="absolute top-20 left-10 transform transition-transform hover:scale-105 hover:shadow-2xl">
        <img src="{{ asset('images/batikKawung.webp') }}" alt="Batik Left" class="h-32">
    </div>
    <div class="absolute top-20 right-10">
        <img src="{{ asset('images/batikKawung.webp') }}" alt="Batik Right" class="h-32 transform scale-x-[-1]">
    </div>
    <div class="absolute bottom-8 left-10">
        <img src="{{ asset('images/batikKawung.webp') }}" alt="Batik Left" class="h-32 transform scale-y-[-1]">
    </div>
    <div class="absolute bottom-8 right-10">
        <img src="{{ asset('images/batikKawung.webp') }}" alt="Batik Right" class="h-32 transform scale-x-[-1] scale-y-[-1]">
    </div>

    <div class="container mx-auto text-center mt-8">
        <h2 class="text-4xl font-bold text-white">
            Yuk kenali <span class="text-kuning1">Dashboard</span> yang ada!
        </h2>
        <div class="mt-10 grid grid-cols-1 md:grid-cols-4 gap-8 px-48">
            <!-- Card 1 -->
            <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-64 translate-x-4 translate-y-2
            transform transition-transform hover:scale-105">
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
            <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-[18rem] -translate-x-2 translate-y-10
            transform transition-transform hover:scale-105">
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
            <div class="bg-white shadow-lg rounded-3xl overflow-hidden h-fit w-[18rem] translate-y-2
            transform transition-transform hover:scale-105">
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
            <div class="bg-white rounded-3xl overflow-hidden h-fit w-64 translate-x-2 translate-y-10
            transform transition-transform hover:scale-105">
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
