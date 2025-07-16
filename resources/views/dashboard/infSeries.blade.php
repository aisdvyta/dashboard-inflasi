@extends('layouts.dashboard')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/dashboard/infseries.css') }}" />
@endpush

@php
    $isBlackWhite = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
@endphp

@section('body')
    <div>
        <x-dash-header
            :tabs="['ASEM 1', 'ASEM 2', 'ASEM 3', 'ATAP']"
            :activeTab="$jenisDataInflasi"
            routeName="dashboard.series"
            :routeParams="[
                'komoditas' => $komoditas,
                'tahun' => $tahun,
                'tahun_check' => request('tahun_check', []),
                'tahun_bulan' => request('tahun_bulan', []),
            ]"
            :showDropdown="false"
            :showExcel="true"
            :showPng="true"
            exportExcelId="exportExcelSeries"
            exportPngId="exportPNG"
            class="mb-4"
        />
    </div>

    <div class="border-t-8 border-biru1">
        <div id="main-dashboard-content" class="p-6 bg-white rounded-b-xl shadow-md {{ $isBlackWhite ? 'grayscale' : '' }}">
            <div class="flex flex-row flex-wrap gap-6 justify-between pb-14">
                <div class="flex flex-col pl-6">
                    <div class="space-y-1 text-biru1">
                        <h1 class="text-5xl font-bold">Dashboard Inflasi Bulanan</h1>
                        <h1 class="text-5xl font-bold">Menurut <span class="text-kuning1">Komoditas {{ ucwords(strtolower($komoditas)) }}</span></h1>
                    </div>
                </div>
                {{-- filter komoditas --}}
                <div class="flex flex-col gap-2 justify-end items-end w-72 pr-8">
                    <form method="GET" action="{{ route('dashboard.series') }}" class="relative w-full">
                        <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                        <div class="flex relative mb-2 w-full">
                            <div class="relative w-full">
                                <select id="komoditas" name="komoditas"
                                    class="px-6 py-2 w-full font-semibold text-white rounded-full shadow appearance-none bg-biru4 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                    onchange="this.form.submit()">
                                    @foreach ($daftarKomoditasUtama as $kom)
                                        <option value="{{ $kom }}" {{ $kom == $komoditas ? 'selected' : '' }}>
                                            {{ ucwords(strtolower($kom)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-6 top-1/2 w-5 h-5 text-white -translate-y-1/2 pointer-events-none"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex flex-row gap-8 px-8">
                <div class="flex-1 grid grid-cols-1 gap-8">
                    <div>
                        <h2 class="font-semibold mb-2">Series Nilai Inflasi Bulanan dalam satu tahun (M-to-M, %)</h2>
                        <div id="chart-mtm" style="height:350px;"></div>
                    </div>
                    <div>
                        <h2 class="font-semibold mb-2">Series Nilai Inflasi Tahun Kalender dalam satu tahun (Y-to-D, %)</h2>
                        <div id="chart-ytd" style="height:350px;"></div>
                    </div>
                    <div>
                        <h2 class="font-semibold mb-2">Series Nilai Inflasi Tahunan dalam satu tahun (Y-to-Y, %)</h2>
                        <div id="chart-yoy" style="height:350px;"></div>
                    </div>
                </div>
                <form method="GET" action="{{ route('dashboard.series') }}"
                    class="w-64 p-4 bg-gray-50 rounded-xl shadow flex flex-col gap-4 h-fit self-start" id="filterFormSide">
                    <input type="hidden" name="komoditas" value="{{ $komoditas }}">
                    <input type="hidden" name="jenis_data_inflasi" value="{{ $jenisDataInflasi }}">
                    <div>
                        <label class="font-bold">Tahun</label><br>
                        <div class="flex flex-col ml-2">
                            @foreach ($tahunList as $th)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="tahun_check[]" value="{{ $th }}"
                                        class="form-checkbox tahun-check"
                                        {{ request()->has('tahun_check') && in_array($th, request('tahun_check', [])) ? 'checked' : '' }}>
                                    <span class="ml-1">{{ $th }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @foreach ($tahunBulanList as $th => $bulanArr)
                        <div class="bulan-group" data-tahun="{{ $th }}"
                            style="display: {{ !request()->has('tahun_check') || in_array($th, request('tahun_check', [])) ? 'block' : 'none' }};">
                            <label class="font-bold">Bulan pada {{ $th }}</label><br>
                            <div class="flex flex-col ml-2">
                                @foreach ($bulanArr as $bln)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="tahun_bulan[]"
                                            value="{{ $th }}-{{ $bln }}"
                                            class="form-checkbox bulan-check"
                                            {{ request()->has('tahun_bulan') && in_array($th . '-' . $bln, request('tahun_bulan', [])) ? 'checked' : '' }}>
                                        <span class="ml-1">{{ $bln }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
            <script>
                // Tampilkan/hilangkan bulan sesuai tahun yang dichecklist dan auto-submit form jika ada perubahan
                document.addEventListener('DOMContentLoaded', function() {
                    const tahunChecks = document.querySelectorAll('#filterFormSide .tahun-check');
                    const bulanChecks = document.querySelectorAll('#filterFormSide .bulan-check');
                    const form = document.getElementById('filterFormSide');
                    tahunChecks.forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            document.querySelectorAll('#filterFormSide .bulan-group').forEach(function(bg) {
                                const tahun = bg.getAttribute('data-tahun');
                                if (document.querySelector(
                                        '#filterFormSide input.tahun-check[value="' + tahun +
                                        '"]:checked')) {
                                    bg.style.display = 'block';
                                } else {
                                    bg.style.display = 'none';
                                }
                            });
                            form.submit();
                        });
                    });
                    bulanChecks.forEach(function(checkbox) {
                        checkbox.addEventListener('change', function() {
                            form.submit();
                        });
                    });
                });
            </script>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/dashboard/infBulananJatim.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="{{ asset('js/dashboard/export-png.js') }}"></script>
    <script>
        window.seriesData = @json($seriesData);
        window.jenisDataInflasi = "{{ $jenisDataInflasi }}";
        window.komoditas = "{{ $komoditas }}";
        window.tahun = "{{ $tahun }}";
        window.tahunList = @json($tahunList);
        window.tahunBulanList = @json($tahunBulanList);
        window.tahunCheck = @json(request('tahun_check', []));
        window.tahunBulan = @json(request('tahun_bulan', []));
        window.daftarKomoditasUtama = @json($daftarKomoditasUtama);
    </script>
    <script src="{{ asset('js/dashboard/infseries-page.js') }}"></script>
@endpush
