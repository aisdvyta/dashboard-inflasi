@extends('layouts.dashboard')

@php
    function getHeatClass($value, $min, $max)
    {
        $percentage = $max - $min != 0 ? ($value - $min) / ($max - $min) : 0;
        if ($percentage >= 0.8) {
            return 'bg-biru4 text-white';
        } elseif ($percentage >= 0.6) {
            return 'bg-biru3 text-white';
        } elseif ($percentage >= 0.4) {
            return 'bg-biru2 text-white';
        } elseif ($percentage >= 0.2) {
            return 'bg-biru5';
        } else {
            return 'bg-white';
        }
    }

    $minInflasiMtM = $topInflasiMtM->min('inflasi');
    $maxInflasiMtM = $topInflasiMtM->max('inflasi');
    $minInflasiYtD = $topInflasiYtD->min('inflasi');
    $maxInflasiYtD = $topInflasiYtD->max('inflasi');
    $minInflasiYoY = $topInflasiYoY->min('inflasi');
    $maxInflasiYoY = $topInflasiYoY->max('inflasi');
    $minAndilMtM = $topInflasiMtM->min('andil');
    $maxAndilMtM = $topInflasiMtM->max('andil');
    $minAndilYtD = $topInflasiYtD->min('andil');
    $maxAndilYtD = $topInflasiYtD->max('andil');
    $minAndilYoY = $topInflasiYoY->min('andil');
    $maxAndilYoY = $topInflasiYoY->max('andil');

@endphp

@section('body')
    <div class="container">
        <div class="px-4 py-4 ">
            <p class="text-4xl font-bold text-biru1">
                <span class="text-kuning1">Jenis</span> Data Inflasi
            </p>
        </div>

        <div class="relative mt-4 flex justify-start border-b-2 border-biru1">
            <a href="#"
                class="flex items-center gap-2 px-10 py-2 rounded-t-xl bg-biru4 hover:bg-biru1 group transition duration-300"
                data-page="tabel">
                <span class="menu-text text-white text-[15px] transition duration-100">
                    ASEM 1</span>
            </a>
            <a href="#"
                class="flex items-center gap-2 px-10 py-2 rounded-t-xl bg-biru4 hover:bg-biru1 group transition duration-300"
                data-page="tabel">
                <span class="menu-text text-white text-[15px] transition duration-100">
                    ASEM 2</span>
            </a>
            <a href="#"
                class="flex items-center gap-2 px-10 py-2 rounded-t-xl bg-biru4 hover:bg-biru1 group transition duration-300"
                data-page="tabel">
                <span class="menu-text text-white text-[15px] transition duration-100">
                    ASEM 3</span>
            </a>
            <a href="#"
                class="flex items-center gap-2 px-12 py-2 rounded-t-xl bg-biru4 hover:bg-biru1 group transition duration-300"
                data-page="tabel">
                <span class="menu-text text-white text-[15px] transition duration-100">
                    ATAP</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md mt-2 p-6">
        <div class="w-full max-w-7xl mx-auto px-6 py-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <!-- Kiri: Judul -->
                <div class="space-y-1">
                    <h1 class="text-5xl md:text-5xl font-bold text-biru1">Dashboard</h1>
                    <h1 class="text-5xl md:text-5xl font-bold text-biru4">INFLASI BULANAN</h1>
                    <h1 class="text-5xl font-bold text-biru1">Provinsi Jawa Timur</h1>
                    <div class="flex gap-4 text-5xl leading-8 text-biru1 pt-2 pr-36 justify-end">
                        <span class="text-right">{{ $bulan }}</span>
                        <span class="text-right">{{ $tahun }}</span>
                    </div>
                </div>

                <!-- Kanan: Kotak Komoditas + Filter -->
                <div>
                    <!-- Kotak Komoditas -->
                    <div
                        class="bg-[#D9EBF8] rounded-xl p-4 shadow-md mb-4 flex flex-col md:flex-row items-center md:items-start justify-between gap-4">
                        <!-- Kiri: Logo dan Deskripsi -->
                        <div class="flex flex-col items-start">
                            <img src="/images/navbar/logoBPS.svg" alt="Logo" class="h-12 mb-2" />
                            <div class="text-sm font-bold text-gray-800">
                                Komoditas yang <br>
                                <span class="text-biru1">
                                    Memiliki<br><span class="text-biru4">Nilai Andil</span><br> Tertinggi dan Terdalam
                                </span>
                            </div>
                        </div>

                        <!-- Kanan: Komoditas -->
                        <div class="flex flex-col gap-2 w-full md:w-auto">
                            <!-- Inflasi Tertinggi -->
                            <div class="text-sm font-semibold">
                                <div class="text-xs font-semibold text-biru4 italic leading-tight">Komoditas dengan Andil Inflasi Tertinggi
                                    (M-to-M, %)</div>
                            </div>
                            <div
                                class="rounded-full bg-merah1 text-white px-6 py-3 flex justify-between items-center w-full md:w-96 shadow-md">
                                <div class="flex items-center gap-4">
                                    <div class="font-semibold text-lg uppercase">{{ $namaKomoditasTertinggi }}</div>
                                    <div class="font-semibold text-lg">{{ number_format($andilTertinggi, 2) }}</div>
                                </div>
                            </div>

                            <!-- Deflasi Terdalam -->
                            {{-- leading itu buat jarak antar hurufnya --}}
                            <div class="text-sm font-semibold">
                                <div class="text-xs font-semibold text-biru4 italic leading-tight">Komoditas dengan Andil Deflasi Terdalam
                                    (M-to-M, %)</div>
                            </div>
                            <div
                                class="rounded-full bg-hijaumuda text-white px-6 py-3 flex justify-between items-center w-full md:w-96 shadow-md">
                                <div class="flex items-center gap-4">
                                    <div class="font-semibold text-lg uppercase">{{ $namaKomoditasTerendah }}</div>
                                    <div class="font-semibold text-lg">{{ number_format($andilTerendah, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Dropdown Filter -->
                    <div class="flex gap-2 justify-end">
                        <form method="GET" action="{{ route('dashboard.bulanan') }}" class="flex gap-2">
                            <!-- Bulan -->
                            <div class="relative">
                                <select id="bulan" name="bulan"
                                    class="appearance-none w-full rounded-xl bg-biru4 text-white font-semibold px-8 py-2 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    @foreach ($daftarPeriode->pluck('bulan')->unique() as $bulanOption)
                                        <option value="{{ $bulanOption }}" {{ $bulanOption == $bulan ? 'selected' : '' }}>
                                            {{ $bulanOption }}
                                        </option>
                                    @endforeach
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <svg id="icon-bulan" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white transition-transform duration-200 rotate-180"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </select>
                            </div>

                            <!-- Tahun -->
                            <div class="relative">
                                <select id="tahun" name="tahun"
                                    class="appearance-none w-full rounded-xl bg-biru4 text-white font-semibold px-8 py-2 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    @foreach ($daftarPeriode->pluck('tahun')->unique() as $tahunOption)
                                        <option value="{{ $tahunOption }}"
                                            {{ $tahunOption == $tahun ? 'selected' : '' }}>
                                            {{ $tahunOption }}
                                        </option>
                                    @endforeach
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <svg id="icon-tahun" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white transition-transform duration-200 rotate-180"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </select>
                            </div>

                            <!-- Tombol Submit -->
                            <button type="submit"
                                class="rounded-xl bg-orange-500 text-white font-semibold px-5 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                                Filter
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>


        <div class="grid grid-cols-3 gap-4 mt-2">
            <div class="col-span-3 flex gap-4">
                {{-- MtM --}}
                <div class="flex flex-col flex-1 gap-2">
                    <div class="text-xs text-white italic leading-tight">?<br><span class="text-biru4">Nilai inflasi pada Bulan saat ini terhadap Bulan sebelumnya</span></div>
                    <div class="bg-biru1 rounded-lg shadow-md px-4 py-2 h-24 flex-1 text-white flex flex-col justify-between">

                        <div class="flex justify-between items-end">
                            <div class="text-sm font-bold">
                                Nilai Inflasi Bulanan<br>
                                <span class="text-xs font-normal italic">(M-to-M, %)</span>
                            </div>
                            <div class="text-5xl font-semibold {{ $inflasiMtM < 0 ? 'text-hijau' : 'text-merah1' }}">
                                {{ number_format($inflasiMtM, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- YtD --}}
                <div class="flex flex-col flex-1 gap-2">
                <div class="text-xs text-biru4 italic leading-tight">Nilai inflasi pada Bulan saat ini terhadap Bulan Desember Tahun sebelumnya</div>
                <div class="bg-biru1 rounded-lg shadow-md px-4 py-2 h-24 flex-1 text-white flex flex-col justify-between">

                    <div class="flex justify-between items-end">
                        <div class="text-sm font-bold">
                            Nilai Inflasi Tahun Kalender<br>
                            <span class="text-xs font-normal italic">(Y-to-D, %)</span>
                        </div>
                        <div class="text-5xl font-semibold {{ $inflasiYtD < 0 ? 'text-hijau' : 'text-merah1' }}">
                            {{ number_format($inflasiYtD, 2) }}
                        </div>
                    </div>
                </div>
                </div>

                {{-- YoY --}}
                <div class="flex flex-col flex-1 gap-2">
                <div class="text-xs text-biru4 italic leading-tight">Nilai inflasi pada Bulan ini di Tahun saat ini terhadap Bulan ini di Tahun sebelumnya</div>
                <div class="bg-biru1 rounded-lg shadow-md px-4 py-2 h-24 flex-1 text-white flex flex-col justify-between">

                    <div class="flex justify-between items-end">
                        <div class="text-sm font-bold">
                            Nilai Inflasi Tahunan<br>
                            <span class="text-xs font-normal italic">(Y-to-Y, %)</span>
                        </div>
                        <div class="text-5xl font-semibold {{ $inflasiYoY < 0 ? 'text-hijau' : 'text-merah1' }}">
                            {{ number_format($inflasiYoY, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 h-80" id="andilmtm"></div>
            <div class="bg-white rounded-lg shadow-md p-4 h-80" id="andilytd"></div>
            <div class="bg-white rounded-lg shadow-md p-4 h-80" id="andilyoy"></div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 h-auto" id="inflasimtm">
                <div class=" shadow-md sm:rounded-lg">
                    <table class=" text-sm text-left rtl:text-right ">
                        <thead class="text-xs bg-biru1 text-white ">
                            <tr>
                                <th scope="col" class="px-2 py-2">Nama Komoditas</th>
                                <th scope="col" class="px-2 py-2">Inflasi MtM</th>
                                <th scope="col" class="px-2 py-2">Andil MtM</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($topInflasiMtM as $item)
                                <tr class="text-biru1">
                                    <td class="px-2 py-2 font-medium">{{ $item->nama_kom }}</td>
                                    <td
                                        class="px-2 py-2 {{ getHeatClass($item->inflasi, $minInflasiMtM, $maxInflasiMtM) }}">
                                        {{ number_format($item->inflasi, 2) }}
                                    </td>
                                    <td class="px-2 py-2 {{ getHeatClass($item->andil, $minAndilMtM, $maxAndilMtM) }}">
                                        {{ number_format($item->andil, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 h-auto" id="inflasiytd">
                <div class=" shadow-md sm:rounded-lg">
                    <table class=" text-sm text-left rtl:text-right ">
                        <thead class="text-xs bg-biru1 text-white ">
                            <tr>
                                <th scope="col" class="px-2 py-2">Nama Komoditas</th>
                                <th scope="col" class="px-2 py-2">Inflasi MtM</th>
                                <th scope="col" class="px-2 py-2">Andil MtM</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($topInflasiYtD as $item)
                                <tr class="text-biru1">
                                    <td class="px-2 py-2 font-medium">{{ $item->nama_kom }}</td>
                                    <td
                                        class="px-2 py-2 {{ getHeatClass($item->inflasi, $minInflasiYtD, $maxInflasiYtD) }}">
                                        {{ number_format($item->inflasi, 2) }}
                                    </td>
                                    <td class="px-2 py-2 {{ getHeatClass($item->andil, $minAndilYtD, $maxAndilYtD) }}">
                                        {{ number_format($item->andil, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 h-auto" id="inflasiyoy">
                <div class=" shadow-md sm:rounded-lg">
                    <table class=" text-sm text-left rtl:text-right ">
                        <thead class="text-xs bg-biru1 text-white ">
                            <tr>
                                <th scope="col" class="px-2 py-2">Nama Komoditas</th>
                                <th scope="col" class="px-2 py-2">Inflasi MtM</th>
                                <th scope="col" class="px-2 py-2">Andil MtM</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($topInflasiYoY as $item)
                                <tr class="text-biru1">
                                    <td class="px-2 py-2 font-medium">{{ $item->nama_kom }}</td>
                                    <td
                                        class="px-2 py-2 {{ getHeatClass($item->inflasi, $minInflasiYoY, $maxInflasiYoY) }}">
                                        {{ number_format($item->inflasi, 2) }}
                                    </td>
                                    <td class="px-2 py-2 {{ getHeatClass($item->andil, $minAndilYoY, $maxAndilYoY) }}">
                                        {{ number_format($item->andil, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const topAndilMtM = @json($topAndilMtM);
        const topAndilYtD = @json($topAndilYtD);
        const topAndilYoY = @json($topAndilYoY);
        const topInflasiMtM = @json($topInflasiMtM);
        const topInflasiYtD = @json($topInflasiYtD);
        const topInflasiYoY = @json($topInflasiYoY);
        window.topAndilMtM = topAndilMtM;
        window.topAndilYtD = topAndilYtD;
        window.topAndilYoY = topAndilYoY;
        window.topInflasiMtM = topInflasiMtM;
        window.topInflasiYtD = topInflasiYtD;
        window.topInflasiYoY = topInflasiYoY;
        console.log("Top Andil MtM:", @json($topAndilMtM));
        console.log("Top Andil YtD:", @json($topAndilYtD));
        console.log("Top Andil YoY:", @json($topAndilYoY));
    </script>
    <script src="{{ asset('js/dashboard/infBulananJatim.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const bulanSelect = document.getElementById('bulan');
    const tahunSelect = document.getElementById('tahun');
    const iconBulan = document.getElementById('icon-bulan');
    const iconTahun = document.getElementById('icon-tahun');

    // Fungsi untuk toggle ikon panah
    function toggleIcon(selectElement, iconElement) {
        selectElement.addEventListener('click', function () {
            if (iconElement.classList.contains('rotate-180')) {
                iconElement.classList.remove('rotate-180');
            } else {
                iconElement.classList.add('rotate-180');
            }
        });
    }

    // Terapkan fungsi toggle pada dropdown bulan dan tahun
    toggleIcon(bulanSelect, iconBulan);
    toggleIcon(tahunSelect, iconTahun);
});
    </script>
@endpush
