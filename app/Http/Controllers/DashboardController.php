<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\detail_inflasi;
use App\Models\master_komoditas;
use App\Models\master_inflasi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function showInflasiBulanan(Request $request)
    {
        // Ambil filter dari request
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('tahun');

        $bulanMap = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];

        // Periode default (terdekat dengan tanggal sekarang)
        $now = Carbon::now();
        $periode = master_inflasi::whereYear('periode', $now->year)
            ->whereMonth('periode', $now->month)
            ->orderBy('periode', 'desc')
            ->value('periode');

        if (!$periode) {
            // Jika bulan sekarang tidak ada, cari periode yang paling dekat
            $periode = master_inflasi::orderByRaw('ABS(DATEDIFF(periode, ?))', [$now])
                ->value('periode');
        }

        // Jika filter bulan dan tahun diberikan, gunakan filter tersebut
        if ($filterBulan && $filterTahun) {
            $bulanAngka = $bulanMap[$filterBulan] ?? null;

            if ($bulanAngka) {
                $periode = master_inflasi::whereYear('periode', $filterTahun)
                    ->whereMonth('periode', $bulanAngka)
                    ->value('periode');
            }
        }

        // Pastikan periode tidak null
        if (!$periode) {
            abort(404, 'Data untuk periode yang diminta tidak ditemukan.');
        }

        // Ambil nilai inflasi berdasarkan periode
        $inflasiMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->value('detail_inflasis.inflasi_mtm');

        $inflasiYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->value('detail_inflasis.inflasi_ytd');

        $inflasiYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->value('detail_inflasis.inflasi_yoy');

        // Cek tanda positif/negatif untuk menentukan urutan
        $orderMtM = $inflasiMtM >= 0 ? 'desc' : 'asc';
        $orderYtD = $inflasiYtD >= 0 ? 'desc' : 'asc';
        $orderYoY = $inflasiYoY >= 0 ? 'desc' : 'asc';

        // Top 10 Inflasi MtM
        $topInflasiMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.inflasi_mtm as inflasi', 'detail_inflasis.andil_mtm as andil')
            ->orderBy('detail_inflasis.andil_mtm', $orderMtM)
            ->take(10)
            ->get();

        // Top 10 Inflasi YtD
        $topInflasiYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.inflasi_ytd as inflasi', 'detail_inflasis.andil_ytd as andil')
            ->orderBy('detail_inflasis.andil_ytd', $orderYtD)
            ->take(10)
            ->get();

        // Top 10 Inflasi YoY
        $topInflasiYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.inflasi_yoy as inflasi', 'detail_inflasis.andil_yoy as andil')
            ->orderBy('detail_inflasis.andil_yoy', $orderYoY)
            ->take(10)
            ->get();

        // Top 10 Andil MtM
        $topAndilMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm as andil')
            ->orderBy('detail_inflasis.andil_mtm', $orderMtM)
            ->take(10)
            ->get();

        // Top 10 Andil YtD
        $topAndilYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.andil_ytd as andil')
            ->orderBy('detail_inflasis.andil_ytd', $orderYtD)
            ->take(10)
            ->get();

        // Top 10 Andil YoY
        $topAndilYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.andil_yoy as andil')
            ->orderBy('detail_inflasis.andil_yoy', $orderYoY)
            ->take(10)
            ->get();

        // Ambil komoditas dengan andil tertinggi (M-to-M)
        $komoditasTertinggi = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode) // Filter periode
            ->orderByDesc('detail_inflasis.andil_mtm') // Urutkan berdasarkan andil tertinggi
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm')
            ->first();

        // Ambil komoditas dengan andil terendah (M-to-M)
        $komoditasTerendah = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode) // Filter periode
            ->orderBy('detail_inflasis.andil_mtm') // Urutkan berdasarkan andil terendah
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm')
            ->first();

        // Pastikan data tidak null
        $namaKomoditasTertinggi = optional($komoditasTertinggi)->nama_kom ?? '-';
        $andilTertinggi = optional($komoditasTertinggi)->andil_mtm ?? 0;

        $namaKomoditasTerendah = optional($komoditasTerendah)->nama_kom ?? '-';
        $andilTerendah = optional($komoditasTerendah)->andil_mtm ?? 0;


        // Periode (bulan dan tahun)
        Carbon::setLocale('id');
        $bulan = Carbon::parse($periode)->isoFormat('MMMM');
        $tahun = Carbon::parse($periode)->format('Y');

        // Daftar semua periode untuk filter dropdown
        $daftarPeriode = master_inflasi::orderBy('periode', 'desc')->get()->map(function ($item) {
            return [
                'bulan' => Carbon::parse($item->periode)->isoFormat('MMMM'),
                'tahun' => Carbon::parse($item->periode)->format('Y'),
            ];
        });

        return view('dashboard.infBulananJatim', compact(
            'inflasiMtM',
            'inflasiYtD',
            'inflasiYoY',
            'topInflasiMtM',
            'topInflasiYtD',
            'topInflasiYoY',
            'topAndilMtM',
            'topAndilYtD',
            'topAndilYoY',
            'namaKomoditasTertinggi',
            'andilTertinggi',
            'namaKomoditasTerendah',
            'andilTerendah',
            'bulan',
            'tahun',
            'daftarPeriode'
        ));
    }
}
