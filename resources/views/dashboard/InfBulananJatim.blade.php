@extends('layouts.dashboard')

@php
    function getHeatClass($value, $min, $max, $top)
    {
        $isPositive = $top > 0;

        if ($isPositive) {
            $percentage = $max - $min != 0 ? ($value - $min) / ($max - $min) : 0;
            if ($percentage >= 0.8) {
                return 'bg-biru2 text-white';
            } elseif ($percentage >= 0.6) {
                return 'bg-biru3 text-white';
            } elseif ($percentage >= 0.4) {
                return 'bg-biru4 text-white';
            } elseif ($percentage >= 0.2) {
                return 'bg-biru5    ';
            } else {
                return 'bg-white';
            }
        } else {
            $percentage = $min - $max != 0 ? ($value - $max) / ($min - $max) : 0;
            if ($percentage >= 0.8) {
                return 'bg-biru2 text-white';
            } elseif ($percentage >= 0.6) {
                return 'bg-biru3 text-white';
            } elseif ($percentage >= 0.4) {
                return 'bg-biru4 text-white';
            } elseif ($percentage >= 0.2) {
                return 'bg-biru5';
            } else {
                return 'bg-white';
            }
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

    // Add condition for black and white mode
    $isBlackWhite = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
@endphp

@section('body')
    <div>
        <div class="flex flex-col items-center justify-between md:flex-row ">
            <div class="relative flex justify-start mt-7">
                @php
                    $tabs = ['ASEM 1', 'ASEM 2', 'ASEM 3', 'ATAP'];
                @endphp
                @auth
                    @foreach ($tabs as $tab)
                        <a href="{{ route('dashboard.bulanan', ['jenis_data_inflasi' => $tab]) }}"
                            class="tab-link flex items-center px-14 py-2 transition-all duration-300 rounded-t-xl {{ $jenisDataInflasi === $tab ? 'bg-biru1 text-white' : 'bg-biru4 text-white' }} hover:bg-biru1 group"
                            data-tab="{{ $tab }}" id="tab-{{ strtolower(str_replace(' ', '-', $tab)) }}">
                            <span class="menu-text text-[15px] font-medium transition duration-100">
                                {{ $tab }}
                            </span>
                        </a>
                    @endforeach
                @endauth
                @guest
                    <a href="{{ route('dashboard.bulanan', ['jenis_data_inflasi' => 'ATAP']) }}"
                        class="tab-link flex items-center px-14 py-2 transition-all duration-300 rounded-t-xl bg-biru1 text-white group"
                        data-tab="ATAP" id="tab-atap">
                        <span class="menu-text text-[15px] font-medium transition duration-100">
                            ATAP
                        </span>
                    </a>
                @endguest
            </div>

            <div class="flex items-start gap-2 ">
                <button id="exportExcel"
                    class="flex items-start gap-2 pl-2 pr-5 py-2 transition duration-300 shadow-xl rounded-xl bg-hijau hover:bg-hijau2 hover:-translate-y-1 group">
                    <img src="{{ asset('images/excelIcon.svg') }}" alt="Ikon Eksport Excel" class="h-6 w-6 icon">
                    <span class="menu-text text-white text-[15px] transition duration-100">
                        Export Excel</span>
                </button>
                <button id="exportPNG"
                    class="flex items-end gap-2 pl-2 pr-5 py-2 transition duration-300 shadow-xl rounded-xl bg-merah1 hover:bg-merah1muda hover:-translate-y-1 group">
                    <img src="{{ asset('images/pdfIcon.svg') }}" alt="Ikon Eksport PNG" class="h-6 w-6 icon">
                    <span class="menu-text text-white text-[15px] transition duration-100">
                        Export PNG</span>
                </button>
            </div>
        </div>
    </div>

    <div class="border-t-8 border-biru1">
        <div class="p-6 bg-white rounded-b-xl shadow-md {{ $isBlackWhite ? 'grayscale' : '' }}">
            <div class="w-full px-6 py-10 mx-auto max-w-7xl {{ $isBlackWhite ? 'grayscale' : '' }}">
                <div class="grid items-start grid-cols-1 gap-6 md:grid-cols-2 {{ $isBlackWhite ? 'grayscale' : '' }}">
                    {{-- judul --}}
                    <div class="space-y-1 {{ $isBlackWhite ? 'grayscale' : '' }}">
                        <h1 class="text-5xl font-bold md:text-5xl text-biru1">Dashboard</h1>
                        <h1 class="text-5xl font-bold md:text-5xl text-biru4">INFLASI BULANAN</h1>
                        <h1 class="text-5xl font-bold text-biru1">Provinsi Jawa Timur</h1>
                        <div class="flex justify-end gap-4 pt-2 text-5xl leading-8 text-biru1 pr-36">
                            <span class="text-right">{{ $bulan }}</span>
                            <span class="text-right">{{ $tahun }}</span>
                        </div>
                    </div>

                    {{-- pojok kanan atas --}}
                    <div class="{{ $isBlackWhite ? 'grayscale' : '' }}">
                        <div
                            class="bg-[#D9EBF8] rounded-xl p-4 shadow-md mb-4 flex flex-col md:flex-row items-center md:items-start justify-between gap-4 {{ $isBlackWhite ? 'grayscale' : '' }}">
                            <div class="flex flex-col items-start">
                                <img src="/images/navbar/logoBPS.svg" alt="Logo" class="h-12 mb-2" />
                                <div class="text-sm font-bold text-gray-800">Komoditas yang <br>
                                    <span class="text-biru1">
                                        Memiliki<br><span class="text-biru4">Nilai Andil</span><br> Tertinggi dan Terdalam
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col w-full gap-2 md:w-auto">
                                <div class="text-sm font-semibold">
                                    <div class="text-xs italic font-semibold leading-tight text-biru4">Komoditas dengan Andil
                                        Inflasi Tertinggi
                                        (M-to-M, %)</div>
                                </div>
                                <div
                                    class="flex items-center justify-between w-full px-6 py-3 text-white rounded-full shadow-md bg-merah1 md:w-96">
                                    <div class="flex items-center gap-4">
                                        <div class="text-lg font-semibold uppercase">{{ $namaKomoditasTertinggi }}</div>
                                        <div class="text-lg font-semibold">{{ number_format($andilTertinggi, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- leading itu buat jarak antar hurufnya --}}
                                <div class="text-sm font-semibold">
                                    <div class="text-xs italic font-semibold leading-tight text-biru4">Komoditas dengan Andil
                                        Deflasi Terdalam
                                        (M-to-M, %)</div>
                                </div>
                                <div
                                    class="flex items-center justify-between w-full px-6 py-3 text-white rounded-full shadow-md bg-hijaumuda md:w-96">
                                    <div class="flex items-center gap-4">
                                        <div class="text-lg font-semibold uppercase">{{ $namaKomoditasTerendah }}</div>
                                        <div class="text-lg font-semibold">{{ number_format($andilTerendah, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Filter -->
                        <div class="flex justify-end gap-2">
                            <form method="GET" action="{{ route('dashboard.bulanan') }}" class="flex gap-2">
                                <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                                <div class="relative">
                                    <select id="bulan" name="bulan"
                                        class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <option value="">Pilih Bulan</option>
                                        @foreach ($daftarPeriode->pluck('bulan')->unique() as $bulanOption)
                                            <option value="{{ $bulanOption }}"
                                                {{ $bulanOption == $bulan ? 'selected' : '' }}>
                                                {{ $bulanOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                        <svg id="icon-bulan" xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="relative">
                                    <select id="tahun" name="tahun"
                                        class="w-full px-6 py-2 pr-10 font-semibold text-white rounded-full shadow-md appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <option value="">Pilih Tahun</option>
                                        @foreach ($daftarPeriode->pluck('tahun')->unique() as $tahunOption)
                                            <option value="{{ $tahunOption }}"
                                                {{ $tahunOption == $tahun ? 'selected' : '' }}>
                                                {{ $tahunOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 flex items-center pointer-events-none right-3">
                                        <svg id="icon-tahun" xmlns="http://www.w3.org/2000/svg"
                                            class="w-5 h-5 text-white transition-transform duration-200 rotate-180"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="px-5 py-2 font-semibold text-white bg-orange-500 rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                                    Filter
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-2">
                <div class="flex items-stretch col-span-3 gap-4">
                    {{-- MtM --}}
                    <div class="flex flex-col flex-1 gap-2">
                        <div class="text-xs italic leading-tight text-white">
                            ?<br>
                            <span class="text-biru4">Nilai inflasi pada Bulan saat ini terhadap Bulan sebelumnya</span>
                        </div>
                        <div
                            class="flex flex-col justify-between flex-1 h-full px-4 py-2 text-white shadow-lg bg-biru1 rounded-2xl">
                            <div class="flex items-end justify-between">
                                <div class="text-sm font-bold">
                                    Nilai Inflasi Bulanan<br>
                                    <span class="text-xs italic font-normal">(M-to-M, %)</span>
                                </div>
                                <div id="inflasiMtM"
                                    class="text-5xl font-semibold {{ $inflasiMtM < 0 ? 'text-hijau' : 'text-merah1' }} {{ $isBlackWhite ? 'text-white' : '' }}">
                                    {{ number_format($inflasiMtM, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- YtD --}}
                    <div class="flex flex-col flex-1 gap-2">
                        <div class="text-xs italic leading-tight text-biru4">
                            Nilai inflasi pada Bulan saat ini terhadap Bulan Desember Tahun sebelumnya
                        </div>
                        <div
                            class="flex flex-col justify-between flex-1 h-full px-4 py-2 text-white shadow-lg bg-biru1 rounded-2xl">
                            <div class="flex items-end justify-between">
                                <div class="text-sm font-bold">
                                    Nilai Inflasi Tahun Kalender<br>
                                    <span class="text-xs italic font-normal">(Y-to-D, %)</span>
                                </div>
                                <div id="inflasiYtD"
                                    class="text-5xl font-semibold {{ $inflasiYtD < 0 ? 'text-hijau' : 'text-merah1' }} {{ $isBlackWhite ? 'text-white' : '' }}">
                                    {{ number_format($inflasiYtD, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- YoY --}}
                    <div class="flex flex-col flex-1 gap-2">
                        <div class="text-xs italic leading-tight text-biru4">
                            Nilai inflasi pada Bulan ini di Tahun saat ini terhadap Bulan ini di Tahun sebelumnya
                        </div>
                        <div
                            class="flex flex-col justify-between flex-1 h-full px-4 py-2 text-white shadow-lg bg-biru1 rounded-2xl">
                            <div class="flex items-end justify-between">
                                <div class="text-sm font-bold">
                                    Nilai Inflasi Tahunan<br>
                                    <span class="text-xs italic font-normal">(Y-to-Y, %)</span>
                                </div>
                                <div id="inflasiYoY"
                                    class="text-5xl font-semibold {{ $inflasiYoY < 0 ? 'text-hijau' : 'text-merah1' }} {{ $isBlackWhite ? 'text-white' : '' }}">
                                    {{ number_format($inflasiYoY, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- js echart barchart --}}
            <div class="grid grid-cols-3 gap-4 mt-4 ">
                <div class="p-3 bg-white border shadow-lg rounded-2xl border-biru1">
                    <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                        10 Komoditas dengan <br> Sumbangan Inflasi Bulanan Terbesar<br>
                        <span class="italic font-normal">(M-to-M, %)</span>
                    </h2>
                    <div class="h-80" id="andilmtm"></div>
                </div>

                <div class="p-3 bg-white border shadow-lg rounded-2xl border-biru1">
                    <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                        10 Komoditas dengan <br> Sumbangan Inflasi Tahun Kalender Terbesar<br>
                        <span class="italic font-normal">(Y-to-D, %)</span>
                    </h2>
                    <div class="h-80" id="andilytd"></div>
                </div>

                <div class="p-3 bg-white border shadow-lg rounded-2xl border-biru1">
                    <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                        10 Komoditas dengan <br> Sumbangan Inflasi Tahunan Terbesar<br>
                        <span class="italic font-normal">(Y-on-Y, %)</span>
                    </h2>
                    <div class="h-80" id="andilyoy"></div>
                </div>
            </div>

            {{-- tabel top komoditas --}}
            <div class="grid grid-cols-3 gap-4 mt-4">
                <div class="h-auto p-4 bg-white border shadow-lg rounded-2xl border-biru1">
                    <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                        10 Komoditas Penyumbang Inflasi Bulanan<br>
                        <span class="italic font-normal">(M-to-M, %)</span>
                    </h2>
                    <div class="shadow-md sm:rounded-lg">
                        <table class="w-full mx-auto text-sm text-left rtl:text-right">
                            <thead class="text-xs text-white bg-biru1">
                                <tr>
                                    <th scope="col" class="px-2 py-2 text-left"> </th>
                                    <th scope="col" class="px-2 py-2 text-left">Nama Komoditas</th>
                                    <th scope="col" class="px-2 py-2 text-right">Inflasi MtM</th>
                                    <th scope="col" class="px-2 py-2 text-right">Andil MtM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topInflasiMtM as $index => $item)
                                    <tr class="text-xs text-biru1">
                                        <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                        <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                        <td
                                            class="px-2 py-2 text-right {{ getHeatClass($item->inflasi, $minInflasiMtM, $maxInflasiMtM, $inflasiMtM) }}">
                                            {{ number_format($item->inflasi, 2, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-2 py-2 text-right {{ getHeatClass($item->andil, $minAndilMtM, $maxAndilMtM, $inflasiMtM) }}">
                                            {{ number_format($item->andil, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="h-auto p-4 bg-white border shadow-lg rounded-2xl border-biru1">
                    <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                        10 Komoditas Penyumbang Inflasi Tahun Kalender<br>
                        <span class="italic font-normal">(Y-to-D, %)</span>
                    </h2>
                    <div class="shadow-md sm:rounded-lg">
                        <table class="w-full mx-auto text-sm text-left rtl:text-right">
                            <thead class="text-xs text-white bg-biru1">
                                <tr>
                                    <th scope="col" class="px-2 py-2 text-left"> </th>
                                    <th scope="col" class="px-2 py-2 text-left">Nama Komoditas</th>
                                    <th scope="col" class="px-2 py-2 text-right">Inflasi YtD</th>
                                    <th scope="col" class="px-2 py-2 text-right">Andil YtD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topInflasiYtD as $index => $item)
                                    <tr class="text-xs text-biru1">
                                        <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                        <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                        <td
                                            class="px-2 py-2 text-right {{ getHeatClass($item->inflasi, $minInflasiYtD, $maxInflasiYtD, $inflasiYtD) }}">
                                            {{ number_format($item->inflasi, 2, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-2 py-2 text-right {{ getHeatClass($item->andil, $minAndilYtD, $maxAndilYtD,$inflasiYtD) }}">
                                            {{ number_format($item->andil, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="h-auto p-4 bg-white border shadow-lg rounded-2xl border-biru1">
                    <h2 class="pb-3 text-sm font-bold leading-tight text-center text-biru1">
                        10 Komoditas Penyumbang Inflasi Tahunan<br>
                        <span class="italic font-normal">(Y-on-Y, %)</span>
                    </h2>
                    <div class="shadow-md sm:rounded-lg">
                        <table class="w-full mx-auto text-sm text-left rtl:text-right">
                            <thead class="text-xs text-white bg-biru1">
                                <tr>
                                    <th scope="col" class="px-2 py-2 text-left"> </th>
                                    <th scope="col" class="px-2 py-2 text-left">Nama Komoditas</th>
                                    <th scope="col" class="px-2 py-2 text-right">Inflasi YoY</th>
                                    <th scope="col" class="px-2 py-2 text-right">Andil YoY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topInflasiYoY as $index => $item)
                                    <tr class="text-xs text-biru1">
                                        <td class="px-2 py-2 text-left">{{ $index + 1 }}</td>
                                        <td class="px-2 py-2 font-normal text-left">{{ $item->nama_kom }}</td>
                                        <td
                                            class="px-2 py-2 text-right {{ getHeatClass($item->inflasi, $minInflasiYoY, $maxInflasiYoY, $inflasiYoY) }}">
                                            {{ number_format($item->inflasi, 2, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-2 py-2 text-right {{ getHeatClass($item->andil, $minAndilYoY, $maxAndilYoY, $inflasiYoY) }}">
                                            {{ number_format($item->andil, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
    </script>
    <script src="{{ asset('js/dashboard/infBulananJatim.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bulanSelect = document.getElementById('bulan');
            const tahunSelect = document.getElementById('tahun');
            const iconBulan = document.getElementById('icon-bulan');
            const iconTahun = document.getElementById('icon-tahun');
            const exportPngBtn = document.getElementById('exportPNG');
            exportPngBtn.innerHTML = `
                <img src="{{ asset('images/pdfIcon.svg') }}" alt="Ikon Eksport PNG" class="h-6 w-6 icon">
                <span class="menu-text text-white text-[15px] transition duration-100">Export PNG</span>
            `;
            exportPngBtn.title = 'Export PNG';
            const exportExcelBtn = document.getElementById('exportExcel');
            const jenisDataInflasi = '{{ $jenisDataInflasi }}';

            // Fungsi untuk toggle ikon panah
            function toggleIcon(selectElement, iconElement) {
                selectElement.addEventListener('click', function() {
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

            // JS EXPORT PDF
            exportPngBtn.addEventListener('click', async function() {
                const loading = document.createElement('div');
                loading.style.position = 'fixed';
                loading.style.top = '40%';
                loading.style.left = '40%';
                loading.style.transform = 'translate(-60%, -60%)';
                loading.style.backgroundColor = 'rgba(0,0,0,0.7)';
                loading.style.color = 'white';
                loading.style.padding = '7px';
                loading.style.borderRadius = '3px';
                loading.style.zIndex = '9999';
                loading.textContent = 'Generating PNG...';
                document.body.appendChild(loading);

                try {
                    // Get the main dashboard content
                    const content = document.querySelector('.bg-white.rounded-b-xl.shadow-md');
                    if (!content) throw new Error('Dashboard content not found');

                    // Wait for charts to render
                    await new Promise(resolve => setTimeout(resolve, 1000));

                    // Use html2canvas to capture screenshot
                    const canvas = await html2canvas(content, {
                            useCORS: true,
                        scale: 2,
                        backgroundColor: null,
                        scrollX: 0,
                        scrollY: 0
                    });
                    const image = canvas.toDataURL('image/png');
                    const bulan = document.getElementById('bulan').value;
                    const tahun = document.getElementById('tahun').value;
                    const jenisDataInflasi = '{{ $jenisDataInflasi }}';
                    const a = document.createElement('a');
                    a.href = image;
                    a.download = `dashboard-inflasi-${bulan}-${tahun}-${jenisDataInflasi}.png`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                } catch (error) {
                    console.error('Error generating PNG:', error);
                    alert('Terjadi kesalahan saat menghasilkan PNG. Silakan coba lagi.');
                } finally {
                    document.body.removeChild(loading);
                }
            });

            // JS EXPORT EXCEL
            exportExcelBtn.addEventListener('click', async function() {
                const loading = document.createElement('div');
                loading.style.position = 'fixed';
                loading.style.top = '50%';
                loading.style.left = '50%';
                loading.style.transform = 'translate(-50%, -50%)';
                loading.style.backgroundColor = 'rgba(0,0,0,0.7)';
                loading.style.color = 'white';
                loading.style.padding = '20px';
                loading.style.borderRadius = '5px';
                loading.style.zIndex = '9999';
                loading.textContent = 'Generating Excel...';
                document.body.appendChild(loading);

                try {
                    const bulan = document.getElementById('bulan').value;
                    const tahun = document.getElementById('tahun').value;

                    // Kirim request ke endpoint export Excel
                    const response = await fetch(
                        `/dashboard/export-excel?bulan=${bulan}&tahun=${tahun}&jenis_data_inflasi=${jenisDataInflasi}`
                    );

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    // Dapatkan blob dari response
                    const blob = await response.blob();

                    // Buat URL untuk download
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download =
                        `10 Komoditas Tertinggi dashboard inflasi ${bulan} ${tahun} ${jenisDataInflasi}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);

                } catch (error) {
                    console.error('Error generating Excel:', error);
                    alert('Terjadi kesalahan saat menghasilkan Excel. Silakan coba lagi.');
                } finally {
                    document.body.removeChild(loading);
                }
            });

            // Get current jenis_data_inflasi from URL
            const urlParams = new URLSearchParams(window.location.search);
            const currentJenis = urlParams.get('jenis_data_inflasi') || 'ATAP';

            // Add click event listeners to tabs
            document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    // Prevent default link behavior
                    e.preventDefault();

                    // Get the href and navigate programmatically
                    const href = this.getAttribute('href');
                    window.location.href = href;
                });
            });

            //baru coba hover tab
            const tabs = document.querySelectorAll('.tab-link');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    // Hapus kelas 'active' dari semua tab
                    tabs.forEach(t => t.classList.remove('bg-biru1', 'text-white'));
                    tabs.forEach(t => t.classList.add('bg-biru4'));

                    // Tambahkan kelas 'active' ke tab yang diklik
                    this.classList.add('bg-biru1', 'text-white');
                    this.classList.remove('bg-biru4');
                });
            });
        });
    </script>
@endpush
