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
            <a href="#" onclick="showSection('dash')" 
                class="flex items-center gap-2 px-4 py-2 rounded-t-xl hover:bg-biru4 group transition duration-300">
                <img src="{{ asset('images/sidebar/bdashboardIcon.svg') }}" alt="Ikon Dashboard Inflasi"
                    class="h-6 w-6 icon group-hover:hidden transition duration-100"
                    data-hover="{{ asset('images/sidebar/pdashboardIcon.svg') }}"
                    data-default="{{ asset('images/sidebar/bdashboardIcon.svg') }}">
                <img src="{{ asset('images/sidebar/pdashboardIcon.svg') }}" alt="Ikon Dashboard Inflasi Hover"
                    class="h-6 w-6 hidden group-hover:block transition duration-100">
                <span
                    class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">
                    Dashboard Inflasi</span>
            </a>
            <a href="#" onclick="showSection('table')" 
                class="flex items-center gap-2 px-4 py-2 rounded-t-xl hover:bg-biru4 group transition duration-300">
                <img src="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}" alt="Ikon Data Inflasi"
                    class="h-6 w-6 icon group-hover:hidden transition duration-100"
                    data-hover="{{ asset('images/sidebar/pdataInflasiIcon.svg') }}"
                    data-default="{{ asset('images/sidebar/bdataInflasiIcon.svg') }}">
                <img src="{{ asset('images/sidebar/pdataInflasiIcon.svg') }}" alt="Ikon Data Inflasi Hover"
                    class="h-6 w-6 hidden group-hover:block transition duration-100">
                <span
                    class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">
                    Daftar Tabel Data Inflasi</span>
            </a>
        </div>

        <div name="dash" id="dash-section" class="block">
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
        <div name="table" id="table-section" class="hidden">
            <p class="mt-4 text-base text-biru1 text-start">Daftar Tabel Inflasi menampilkan daftar data inflasi terkini di wilayah Provinsi Jawa Timur.</p>

            <div class="bg-white shadow-md rounded-xl p-4 z-10 mt-3">
                <table class="w-full">
                    <thead class="w-5/6 mx-auto border-b border-abubiru mb-10 mt-10">
                        <tr class="text-biru1">
                            <th class="px-4 py-2 text-left">No.</th>
                            <th class="px-4 py-2 text-left">Nama Data</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($uploads as $index => $upload)
                            <tr>
                                <td class="px-4 py-4 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 hover:underline hover:text-biru4">
                                    <a href="{{ route('manajemen-data-inflasi.show', $upload->nama) }}">
                                        {{ $upload->nama }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex place-content-center gap-3">
                                        <!-- Tombol Lihat -->
                                        <a href="#"
                                            class="flex items-center gap-1 bg-biru1 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 text-sm font-normal">
                                            <img src="{{ asset('images/eyeIcon.svg') }}" alt="Lihat Icon"
                                                class="h-5 w-5">
                                            Lihat Data
                                        </a>
            
                                        <!-- Tombol Unduh -->
                                        <button type="button" onclick="#"
                                            class="flex items-center gap-1 bg-hijau text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1 text-sm font-normal">
                                            <img src="{{ asset('images/excelIcon.svg') }}" alt="Excel Icon"
                                                class="h-5 w-5">
                                            Unduh Data
                                        </button>
            
                                        @include('components.modaKonfirmasiHapus', [
                                            'id' => $upload->id,
                                            'folderName' => 'manajemen-data-inflasi',
                                            'formAction' => route('manajemen-data-inflasi.destroy', $upload->id),
                                        ])
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    @if ($search)
                                        Tidak ada hasil untuk pencarian "{{ $search }}".
                                    @else
                                        Tidak ada data komoditas.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $uploads->links('components.pagination') }}
                </div>
            </div>
    
        </div>
    </div>

</div>


<script>
    function showSection(section) {
        const dashSection = document.getElementById('dash-section');
        const tableSection = document.getElementById('table-section');

        if (section === 'dash') {
            dashSection.classList.remove('hidden');
            dashSection.classList.add('block');
            tableSection.classList.remove('block');
            tableSection.classList.add('hidden');
        } else if (section === 'table') {
            tableSection.classList.remove('hidden');
            tableSection.classList.add('block');
            dashSection.classList.remove('block');
            dashSection.classList.add('hidden');
        }
    }
</script>