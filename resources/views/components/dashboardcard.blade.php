<div id="main2" class="min-h-[95vh] mb-12 flex justify-center">
    <div class="container px-8 md:px-24 lg:px-48">
        <div class="border-b-2 pb-4 border-biru5">
            <h1 class="text-3xl font-bold text-kuning1 text-start">Fitur <span class="text-biru1">yang tersedia</span></h1>
        </div>

        <div class="relative mt-4 flex justify-start border-b-2 border-biru4">
            <button id="btn-dashboard" onclick="showSection('dash')" class="flex items-center gap-2 px-4 py-2 rounded-t-xl">
                <img src="{{ asset('images/sidebar/pdashboardIcon.svg') }}" alt="Dashboard Icon" class="w-5 h-5 menu-icon">
                <span class="menu-text text-white">Dashboard Inflasi</span>
            </button>
            
            <button id="btn-table" onclick="showSection('table')" class="flex items-center gap-2 px-4 py-2 rounded-t-xl">
                <img src="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}" alt="Table Icon" class="w-5 h-5 menu-icon">
                <span class="menu-text text-biru1">Daftar Tabel Data Inflasi</span>
            </button>
        </div>

        <div name="dash" id="dash-section" class="block">
            <p class="mt-4 text-base text-biru1 text-start">Dashboard Inflasi menyajikan data inflasi terkini di wilayah Provinsi Jawa Timur melalui visualisasi yang interaktif dan informatif.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                <!-- Card 1: Dashboard Inflasi Spasial -->
                <div class="bg-white shadow-xl rounded-2xl p-6 flex flex-col items-center hover:shadow-2xl transition duration-300">
                    <img src="{{ asset('images/landingMain3/dashInflasiSpasial.svg') }}" alt="Dashboard Spasial" class="rounded-lg h-48 w-48 object-top object-cover mb-4 border border-biru5">
                    <h2 class="text-lg text-biru1 font-semibold mb-2 text-center">Dashboard Inflasi Bulanan Provinsi Jawa Timur</h2>
                    <p class="text-base text-biru1 text-center mb-4">Menyajikan visualisasi data inflasi bulanan di Kab/Kota IHK wilayah Provinsi Jawa Timur.</p>
                    <a href="{{ route('dashboard.spasial') }}" class="text-base text-white bg-biru4 px-4 py-2 rounded-lg shadow-lg hover:bg-biru1 hover:translate-x-2 transition-all duration-300">Lihat Selengkapnya →</a>
                </div>
                <!-- Card 2: Dashboard Inflasi Kelompok -->
                <div class="bg-white shadow-xl rounded-2xl p-6 flex flex-col items-center hover:shadow-2xl transition duration-300">
                    <img src="{{ asset('images/landingMain3/dashInflasiKelompok.svg') }}" alt="Dashboard Kelompok" class="rounded-lg h-48 w-48 object-top object-cover mb-4 border border-biru5">
                    <h2 class="text-lg text-biru1 font-semibold mb-2 text-center">Dashboard Inflasi Menurut Kelompok Pengeluaran</h2>
                    <p class="text-base text-biru1 text-center mb-4">Menyajikan visualisasi data inflasi bulanan menurut kelompok pengeluaran di Jawa Timur.</p>
                    <a href="{{ route('dashboard.kelompok') }}" class="text-base text-white bg-biru4 px-4 py-2 rounded-lg shadow-lg hover:bg-biru1 hover:translate-x-2 transition-all duration-300">Lihat Selengkapnya →</a>
                </div>
                <!-- Card 3: Dashboard Series Inflasi -->
                <div class="bg-white shadow-xl rounded-2xl p-6 flex flex-col items-center hover:shadow-2xl transition duration-300">
                    <img src="{{ asset('images/landingMain3/dashSeriesInflasi.svg') }}" alt="Dashboard Series" class="rounded-lg h-48 w-48 object-top object-cover mb-4 border border-biru5">
                    <h2 class="text-lg text-biru1 font-semibold mb-2 text-center">Dashboard Series Inflasi Provinsi Jawa Timur</h2>
                    <p class="text-base text-biru1 text-center mb-4">Menyajikan visualisasi data series inflasi di wilayah Provinsi Jawa Timur.</p>
                    <a href="{{ route('dashboard.series') }}" class="text-base text-white bg-biru4 px-4 py-2 rounded-lg shadow-lg hover:bg-biru1 hover:translate-x-2 transition-all duration-300">Lihat Selengkapnya →</a>
                </div>
            </div>
        </div>
        <div name="table" id="table-section" class="hidden">
            <p class="mt-4 text-base text-biru1 text-start">Daftar Tabel Inflasi menampilkan daftar data inflasi ATAP terkini di wilayah Provinsi Jawa Timur.</p>
            <div class="bg-white shadow-md rounded-xl p-4 z-10 mt-3">
                <table class="w-full">
                    <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                        <tr class="text-biru1">
                            <th class="px-4 py-2 text-center">No.</th>
                            <th class="px-4 py-2 text-left">Nama Data</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($uploads as $index => $upload)
                            <tr>
                                <td class="px-4 py-4 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $upload->display_name }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex place-content-center gap-3">
                                        <a href="{{ route('daftar-tabel-inflasi.show', $upload->id) }}"
                                            class="flex items-center gap-1 bg-biru4 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 transition duration-300">
                                            <img src="{{ asset('images/eyeIcon.svg') }}" alt="View Icon"
                                                class="h-5 w-5">
                                            Lihat Data
                                        </a>
                                        <a href="{{ route('daftar-tabel-inflasi.download', $upload->id) }}"
                                            class="flex items-center gap-1 bg-hijaumuda text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 transition duration-300">
                                            <img src="{{ asset('images/excelIcon.svg') }}" alt="Download Icon"
                                                class="h-5 w-5">
                                            Unduh Data
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                    Belum ada data inflasi ATAP yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $uploads->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function showSection(section) {
        const dashSection = document.getElementById('dash-section');
        const tableSection = document.getElementById('table-section');
        const btnDashboard = document.getElementById('btn-dashboard');
        const btnTable = document.getElementById('btn-table');

        // Ganti icon berdasarkan kondisi
        const dashboardIcon = btnDashboard.querySelector('.menu-icon');
        const tableIcon = btnTable.querySelector('.menu-icon');

        if (section === 'dash') {
            dashSection.classList.remove('hidden');
            dashSection.classList.add('block');
            tableSection.classList.remove('block');
            tableSection.classList.add('hidden');

            // Aktifkan tombol dashboard
            btnDashboard.classList.add('bg-biru1');
            btnDashboard.querySelector('.menu-text').classList.add('text-white');
            btnDashboard.querySelector('.menu-text').classList.remove('text-biru1');
            dashboardIcon.src = "{{ asset('images/sidebar/pdashboardIcon.svg') }}";

            // Nonaktifkan tombol table
            btnTable.classList.remove('bg-biru1');
            btnTable.querySelector('.menu-text').classList.remove('text-white');
            btnTable.querySelector('.menu-text').classList.add('text-biru1');
            tableIcon.src = "{{ asset('images/sidebar/bdataInflasiIcon.svg') }}";

        } else if (section === 'table') {
            tableSection.classList.remove('hidden');
            tableSection.classList.add('block');
            dashSection.classList.remove('block');
            dashSection.classList.add('hidden');

            // Aktifkan tombol table
            btnTable.classList.add('bg-biru1');
            btnTable.querySelector('.menu-text').classList.add('text-white');
            btnTable.querySelector('.menu-text').classList.remove('text-biru1');
            tableIcon.src = "{{ asset('images/sidebar/pdataInflasiIcon.svg') }}";

            // Nonaktifkan tombol dashboard
            btnDashboard.classList.remove('bg-biru1');
            btnDashboard.querySelector('.menu-text').classList.remove('text-white');
            btnDashboard.querySelector('.menu-text').classList.add('text-biru1');
            dashboardIcon.src = "{{ asset('images/sidebar/bdashboardIcon.svg') }}";
        }
    }

    // Aktifkan dashboard saat halaman dimuat
    window.addEventListener('DOMContentLoaded', () => {
        showSection('dash');
    });
</script>
