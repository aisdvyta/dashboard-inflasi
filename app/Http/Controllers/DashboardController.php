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

        // Ini bikin periode default (kalo gaada, diambil yang terdekat dengan tanggal sekarang)w
        $now = Carbon::now();
        $periode = master_inflasi::whereYear('periode', $now->year)
            ->whereMonth('periode', $now->month)
            ->orderBy('periode', 'desc')
            ->value('periode');

        if (!$periode) {
            $periode = master_inflasi::orderByRaw('ABS(DATEDIFF(periode, ?))', [$now])
                ->value('periode');
        }

        // Ini bikin filter bulan dan tahun
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('tahun');

        if ($filterBulan && $filterTahun) {
            $bulanAngka = $bulanMap[$filterBulan] ?? null;
            if ($bulanAngka) {
                $periode = master_inflasi::whereYear('periode', $filterTahun)
                    ->whereMonth('periode', $bulanAngka)
                    ->value('periode');
            }
        }

        if (!$periode) {
            abort(404, 'Data untuk periode yang diminta tidak ditemukan.');
        }

        // Bikin Periode (bulan dan tahun)
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

        // NGATUR VIEW POJOK KANAN ATAS
        $komoditasTertinggi = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // ngambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // ngambil wilayah
            ->where('master_inflasis.periode', $periode) // ngambil periode
            ->orderByDesc('detail_inflasis.andil_mtm') // urut andil tertinggi
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm')
            ->first();

        $komoditasTerendah = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // ngambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // ngambil wilayah
            ->where('master_inflasis.periode', $periode) // ngambil periode
            ->orderBy('detail_inflasis.andil_mtm') // urut andil terendah
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm')
            ->first();

        $namaKomoditasTertinggi = optional($komoditasTertinggi)->nama_kom ?? '-';
        $andilTertinggi = optional($komoditasTertinggi)->andil_mtm ?? 0;

        $namaKomoditasTerendah = optional($komoditasTerendah)->nama_kom ?? '-';
        $andilTerendah = optional($komoditasTerendah)->andil_mtm ?? 0;

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

        $topInflasiMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.inflasi_mtm as inflasi', 'detail_inflasis.andil_mtm as andil')
            ->orderBy('detail_inflasis.andil_mtm', $orderMtM)
            ->take(10)
            ->get();

        $topInflasiYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.inflasi_ytd as inflasi', 'detail_inflasis.andil_ytd as andil')
            ->orderBy('detail_inflasis.andil_ytd', $orderYtD)
            ->take(10)
            ->get();

        $topInflasiYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.inflasi_yoy as inflasi', 'detail_inflasis.andil_yoy as andil')
            ->orderBy('detail_inflasis.andil_yoy', $orderYoY)
            ->take(10)
            ->get();

        $topAndilMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm as andil')
            ->orderBy('detail_inflasis.andil_mtm', $orderMtM)
            ->take(10)
            ->get();

        $topAndilYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.andil_ytd as andil')
            ->orderBy('detail_inflasis.andil_ytd', $orderYtD)
            ->take(10)
            ->get();

        $topAndilYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3) // Hanya ambil flag 3
            ->where('detail_inflasis.id_wil', 3500) // Filter wilayah
            ->where('master_inflasis.periode', $periode)
            ->select('mk.nama_kom', 'detail_inflasis.andil_yoy as andil')
            ->orderBy('detail_inflasis.andil_yoy', $orderYoY)
            ->take(10)
            ->get();

        return view('dashboard.infBulananJatim', compact(
            'bulan',
            'tahun',
            'daftarPeriode',
            'namaKomoditasTertinggi',
            'andilTertinggi',
            'namaKomoditasTerendah',
            'andilTerendah',
            'inflasiMtM',
            'inflasiYtD',
            'inflasiYoY',
            'topInflasiMtM',
            'topInflasiYtD',
            'topInflasiYoY',
            'topAndilMtM',
            'topAndilYtD',
            'topAndilYoY',
        ));
    }
}
