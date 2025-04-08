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
<div class="container bg-white rounded-lg shadow-md">
    <div class="px-4 py-4 border-b-2 border-biru5">
        <p class="text-3xl font-semibold text-biru1">
            Jenis Data Inflasi
        </p>
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
                class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">
                Dashboard Inflasi</span>
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
                class="menu-text text-biru1 font-semibold text-[15px] group-hover:text-white transition duration-100">
                Daftar Tabel Data Inflasi</span>
        </a>
    </div>
</div>

    <div class="bg-white rounded-lg shadow-md mt-6 p-6">
        <div class="w-full max-w-7xl mx-auto px-6 py-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <!-- Kiri: Judul -->
                <div class="space-y-2">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800">
                        Dashboard <span class="text-kuning1">Inflasi Bulanan</span>
                    </h1>
                    <h2 class="text-2xl font-semibold text-gray-800">Provinsi Jawa Timur</h2>
                    <div class="flex gap-10 text-xl text-gray-700 pt-2">
                        <span>{{ $bulan }}</span>
                        <span>{{ $tahun }}</span>
                    </div>
                </div>

                <!-- Kanan: Kotak Komoditas + Filter -->
                <div>
                    <!-- Kotak Komoditas -->
                    <div
                        class="bg-[#D9EBF8] rounded-xl p-4 shadow-md mb-4 flex flex-col md:flex-row items-center md:items-start justify-between gap-4">
                        <!-- Kiri: Logo dan Deskripsi -->
                        <div class="flex flex-col items-start">
                            <img src="/images/logo.png" alt="Logo" class="h-10 mb-2" />
                            <div class="text-sm font-semibold text-gray-800">
                                Komoditas yang<br>
                                <span class="text-gray-900">
                                    Memiliki <span class="text-biru4">Nilai Andil</span><br> Tertinggi dan Terdalam
                                </span>
                            </div>
                        </div>

                        <!-- Kanan: Komoditas -->
                        <div class="flex flex-col gap-2 w-full md:w-auto">
                            <!-- Inflasi Tertinggi -->
                            <div class="text-sm font-semibold">
                                <div class="text-xs font-normal">Komoditas dengan Andil Inflasi Tertinggi (M-to-M, %)</div>
                            </div>
                            <div class="rounded-full bg-merah1 text-white px-6 py-4 flex justify-between items-center w-full md:w-96 shadow-md">
                                <div class="flex items-center gap-4">
                                    <div class="font-bold text-lg uppercase">{{ $namaKomoditasTertinggi }}</div>
                                    <div class="font-bold text-lg">{{ number_format($andilTertinggi, 2) }}</div>
                                </div>
                            </div>

                            <!-- Deflasi Terdalam -->
                            <div class="text-sm font-semibold">
                                <div class="text-xs font-normal">Komoditas dengan Andil Deflasi Terdalam (M-to-M, %)</div>
                            </div>
                            <div
                                class="rounded-full bg-hijaumuda text-white px-6 py-4 flex justify-between items-center w-full md:w-96 shadow-md">
                                <div class="flex items-center gap-4">
                                    <div class="font-bold text-lg uppercase">{{ $namaKomoditasTerendah }}</div>
                                    <div class="font-bold text-lg">{{ number_format($andilTerendah, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Dropdown Filter -->
                    <div class="flex gap-4 justify-end">
                        <form method="GET" action="{{ route('dashboard.bulanan') }}" class="flex gap-4">
                            <!-- Bulan -->
                            <div class="relative">
                                <select id="bulan" name="bulan"
                                    class="appearance-none w-full rounded-full bg-biru3 text-white font-semibold px-5 py-1.5 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    @foreach ($daftarPeriode->pluck('bulan')->unique() as $bulanOption)
                                        <option value="{{ $bulanOption }}" {{ $bulanOption == $bulan ? 'selected' : '' }}>
                                            {{ $bulanOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tahun -->
                            <div class="relative">
                                <select id="tahun" name="tahun"
                                    class="appearance-none w-full rounded-full bg-biru3 text-white font-semibold px-5 py-1.5 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    @foreach ($daftarPeriode->pluck('tahun')->unique() as $tahunOption)
                                        <option value="{{ $tahunOption }}"
                                            {{ $tahunOption == $tahun ? 'selected' : '' }}>
                                            {{ $tahunOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tombol Submit -->
                            <button type="submit"
                                class="rounded-full bg-orange-500 text-white font-semibold px-5 py-1.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                                Filter
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>


        <div class="grid grid-cols-3 gap-4 mt-4">
            <div class="col-span-3 flex gap-4">
                {{-- MtM --}}
                <div class="bg-[#002147] rounded-lg shadow-md p-4 h-24 flex-1 text-white flex flex-col justify-between">
                    <div class="text-xs leading-tight">Nilai inflasi pada Bulan saat ini terhadap Bulan sebelumnya</div>

                    <div class="flex justify-between items-end mt-2">
                        <div class="text-sm font-bold">
                            Nilai Inflasi Bulanan<br>
                            <span class="text-xs font-normal">(MtM, %)</span>
                        </div>
                        <div class="text-2xl font-bold {{ $inflasiMtM < 0 ? 'text-hijau' : 'text-merah1' }}">
                            {{ number_format($inflasiMtM, 2) }}
                        </div>
                    </div>
                </div>

                {{-- YtD --}}
                <div class="bg-[#002147] rounded-lg shadow-md p-4 h-24 flex-1 text-white flex flex-col justify-between">
                    <div class="text-xs leading-tight">Nilai inflasi pada Bulan saat ini terhadap Bulan Desember Tahun
                        sebelumnya</div>

                    <div class="flex justify-between items-end mt-2">
                        <div class="text-sm font-bold">
                            Nilai Inflasi Tahun Kalender<br>
                            <span class="text-xs font-normal">(YtD, %)</span>
                        </div>
                        <div class="text-2xl font-bold {{ $inflasiYtD < 0 ? 'text-hijau' : 'text-merah1' }}">
                            {{ number_format($inflasiYtD, 2) }}
                        </div>
                    </div>
                </div>

                {{-- YoY --}}
                <div class="bg-[#002147] rounded-lg shadow-md p-4 h-24 flex-1 text-white flex flex-col justify-between">
                    <div class="text-xs leading-tight">Nilai inflasi pada Bulan ini di Tahun saat ini terhadap Bulan ini di
                        Tahun sebelumnya</div>

                    <div class="flex justify-between items-end mt-2">
                        <div class="text-sm font-bold">
                            Nilai Inflasi Tahunan<br>
                            <span class="text-xs font-normal">(YoY, %)</span>
                        </div>
                        <div class="text-2xl font-bold {{ $inflasiYoY < 0 ? 'text-hijau' : 'text-merah1' }}">
                            {{ number_format($inflasiYoY, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 h-56" id="andilmtm"></div>
            <div class="bg-white rounded-lg shadow-md p-4 h-56" id="andilytd"></div>
            <div class="bg-white rounded-lg shadow-md p-4 h-56" id="andilyoy"></div>
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md p-4 h-auto" id="inflasimtm">
                <div class=" shadow-md sm:rounded-lg">
                    <table class=" text-sm text-left rtl:text-right ">
                        <thead class="text-xs uppercase bg-black text-white ">
                            <tr>
                                <th scope="col" class="px-2 py-2">Nama Komoditas</th>
                                <th scope="col" class="px-2 py-2">Inflasi MtM</th>
                                <th scope="col" class="px-2 py-2">Andil MtM</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($topInflasiMtM as $item)
                                <tr class="text-biru4">
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
                        <thead class="text-xs uppercase bg-black text-white ">
                            <tr>
                                <th scope="col" class="px-2 py-2">Nama Komoditas</th>
                                <th scope="col" class="px-2 py-2">Inflasi MtM</th>
                                <th scope="col" class="px-2 py-2">Andil MtM</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($topInflasiYtD as $item)
                                <tr class="text-biru4">
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
                        <thead class="text-xs uppercase bg-black text-white ">
                            <tr>
                                <th scope="col" class="px-2 py-2">Nama Komoditas</th>
                                <th scope="col" class="px-2 py-2">Inflasi MtM</th>
                                <th scope="col" class="px-2 py-2">Andil MtM</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($topInflasiYoY as $item)
                                <tr class="text-biru4">
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
@endpush
