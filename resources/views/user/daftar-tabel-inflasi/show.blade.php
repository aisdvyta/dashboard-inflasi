@extends('layouts.dashboard')

@section('body')
    <div class="container mx-auto p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-4xl font-bold text-biru4"><span class="font-bold text-biru1">Detail</span> Data Inflasi</h2>
                <p class="text-gray-600 mt-1">{{ $displayName }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('daftar-tabel-inflasi.index') }}"
                    class="flex items-center gap-1 bg-gray-500 text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('daftar-tabel-inflasi.download', $upload->id) }}"
                    class="flex items-center gap-1 bg-hijaumuda text-white px-3 py-1 rounded-lg shadow-lg hover:-translate-y-1">
                    <img src="{{ asset('images/excelIcon.svg') }}" alt="Download Icon" class="h-5 w-5">
                    Unduh Excel
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-4 z-10">
            <div class="flex items-center gap-2 px-4 py-1 bg-gray-100 rounded-xl mb-4 w-fit">
                <img src="{{ asset('images/sidebar/searchIcon.svg') }}" alt="Search Icon" class="h-5 w-10">
                <form method="GET" action="{{ route('daftar-tabel-inflasi.show', $upload->id) }}"
                    class="flex items-center">
                    <input type="text" name="search" placeholder="Cari kelompok pengeluaran..."
                        value="{{ $search }}" class="border-none outline-none bg-transparent">
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-abubiru">
                        <tr class="text-biru1">
                            <th class="px-4 py-2 text-center">No.</th>
                            <th class="px-4 py-2 text-left">Nama Kelompok Pengeluaran</th>
                            <th class="px-4 py-2 text-center">Inflasi MtM (%)</th>
                            <th class="px-4 py-2 text-center">Inflasi YtD (%)</th>
                            <th class="px-4 py-2 text-center">Inflasi YoY (%)</th>
                            <th class="px-4 py-2 text-center">Andil MtM (%)</th>
                            <th class="px-4 py-2 text-center">Andil YtD (%)</th>
                            <th class="px-4 py-2 text-center">Andil YoY (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($details as $index => $detail)
                            <tr class="hover:bg-gray-50 font-normal text-sm text-biru1">
                                <td class="px-4 py-3 text-center">{{ $details->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <span class="">{{ $detail->nama_kom }}</span>
                                </td>
                                <td class="px-4 py-3 text-center ">
                                    {{ number_format($detail->inflasi_MtM, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center ">
                                    {{ number_format($detail->inflasi_YtD, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center ">
                                    {{ number_format($detail->inflasi_YoY, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center ">
                                    {{ number_format($detail->andil_MtM, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ number_format($detail->andil_YtD, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center ">
                                    {{ number_format($detail->andil_YoY, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                                    Tidak ada data yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($details->hasPages())
                <div class="mt-4">
                    {{ $details->appends(['search' => $search])->links() }}
                </div>
            @endif

            <div class="mt-6 p-4 bg-biru5 rounded-lg">
                <h3 class="font-semibold text-biru1 mb-2">Keterangan:</h3>
                <ul class="text-sm text-biru1 space-y-1">
                    <li><strong>MtM (Month to Month):</strong> Perubahan inflasi dibanding bulan sebelumnya</li>
                    <li><strong>YtD (Year to Date):</strong> Perubahan inflasi sejak awal tahun</li>
                    <li><strong>YoY (Year over Year):</strong> Perubahan inflasi dibanding bulan yang sama tahun lalu</li>
                    <li><strong>Andil:</strong> Kontribusi kelompok terhadap total inflasi</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
