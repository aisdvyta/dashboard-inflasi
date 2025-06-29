<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\detail_inflasi;
use App\Models\master_komoditas;
use App\Models\master_inflasi;
use Carbon\Carbon;
use App\Exports\InflasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    private $bulanMap = [
        'Januari' => 1,
        'January' => 1,
        'Februari' => 2,
        'February' => 2,
        'Maret' => 3,
        'March' => 3,
        'April' => 4,
        'Mei' => 5,
        'May' => 5,
        'Juni' => 6,
        'June' => 6,
        'Juli' => 7,
        'July' => 7,
        'Agustus' => 8,
        'August' => 8,
        'September' => 9,
        'Oktober' => 10,
        'October' => 10,
        'November' => 11,
        'Desember' => 12,
        'December' => 12
    ];

    private function getTopDataSpasial($idwill, $periode, $jenisDataInflasi, $columnInflasi, $columnAndil, $isNegative = false)
    {
        $query = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3)
            ->where('detail_inflasis.id_wil', $idwill)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->select('mk.nama_kom', "detail_inflasis.{$columnInflasi} as inflasi", "detail_inflasis.{$columnAndil} as andil");

        // Jika negatif, ambil 10 terbawah, jika positif ambil 10 teratas
        if ($isNegative) {
            $query->orderBy($columnAndil); // Terbawah
        } else {
            $query->orderByDesc($columnAndil); // Teratas
        }

        return $query->take(10)->get();
    }

    private function getTopData($periode, $jenisDataInflasi, $columnInflasi, $columnAndil, $isNegative = false, $idWil = 3500)
    {
        $query = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->select('mk.nama_kom', "detail_inflasis.{$columnInflasi} as inflasi", "detail_inflasis.{$columnAndil} as andil");

        // Jika negatif, ambil 10 terbawah, jika positif ambil 10 teratas
        if ($isNegative) {
            $query->orderBy($columnAndil); // Terbawah
        } else {
            $query->orderByDesc($columnAndil); // Teratas
        }

        return $query->take(10)->get();
    }

    public function showInflasiBulanan(Request $request)
    {
        // Get jenis_data_inflasi from request, default to ATAP
        $jenisDataInflasi = $request->input('jenis_data_inflasi', 'ATAP');

        // Get bulan and tahun from request
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Get all available periods for the filter dropdown
        $daftarPeriode = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('periode', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'bulan' => Carbon::parse($item->periode)->translatedFormat('F'),
                    'tahun' => Carbon::parse($item->periode)->format('Y'),
                ];
            });

        // If bulan and tahun are provided, use them
        if ($bulan && $tahun) {
            $bulanAngka = $this->bulanMap[$bulan] ?? null;
            if (!$bulanAngka) {
                // Try to convert English month to Indonesian
                $bulanIndo = Carbon::createFromFormat('F', $bulan)->translatedFormat('F');
                $bulanAngka = $this->bulanMap[$bulanIndo] ?? null;

                if (!$bulanAngka) {
                    abort(404, 'Bulan tidak valid');
                }
            }
            $periode = Carbon::createFromDate($tahun, $bulanAngka, 1)->startOfMonth();
        } else {
            // Otherwise, get the most recent data for the selected jenis_data_inflasi
            $latestData = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
                ->orderBy('periode', 'desc')
                ->first();

            if (!$latestData) {
                abort(404, 'Data tidak ditemukan untuk jenis data inflasi yang dipilih.');
            }

            $periode = Carbon::parse($latestData->periode);
        }

        // Get bulan and tahun from the selected period
        $bulan = $periode->translatedFormat('F');
        $tahun = $periode->format('Y');

        // Get the highest and lowest contributing commodities
        $komoditasTertinggi = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3)
            ->where('detail_inflasis.id_wil', 3500)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderByDesc('detail_inflasis.andil_mtm')
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm')
            ->first();

        $komoditasTerendah = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3)
            ->where('detail_inflasis.id_wil', 3500)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('detail_inflasis.andil_mtm')
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm')
            ->first();

        $namaKomoditasTertinggi = optional($komoditasTertinggi)->nama_kom ?? '-';
        $andilTertinggi = optional($komoditasTertinggi)->andil_mtm ?? 0;

        $namaKomoditasTerendah = optional($komoditasTerendah)->nama_kom ?? '-';
        $andilTerendah = optional($komoditasTerendah)->andil_mtm ?? 0;

        // Get inflation values
        $inflasiMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', 3500)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->value('detail_inflasis.inflasi_mtm');

        $inflasiYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', 3500)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->value('detail_inflasis.inflasi_ytd');

        $inflasiYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', 3500)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->value('detail_inflasis.inflasi_yoy');

        $isMtMNegative = $inflasiMtM < 0;
        $isYtDNegative = $inflasiYtD < 0;
        $isYoYNegative = $inflasiYoY < 0;

        // Ambil data top inflasi
        $topInflasiMtM = $this->getTopData($periode, $jenisDataInflasi, 'inflasi_mtm', 'andil_mtm', $isMtMNegative);
        $topInflasiYtD = $this->getTopData($periode, $jenisDataInflasi, 'inflasi_ytd', 'andil_ytd', $isYtDNegative);
        $topInflasiYoY = $this->getTopData($periode, $jenisDataInflasi, 'inflasi_yoy', 'andil_yoy', $isYoYNegative);

        // Ambil data top andil
        $topAndilMtM = $this->getTopData($periode, $jenisDataInflasi, 'inflasi_mtm', 'andil_mtm', $isMtMNegative);
        $topAndilYtD = $this->getTopData($periode, $jenisDataInflasi, 'inflasi_ytd', 'andil_ytd', $isYtDNegative);
        $topAndilYoY = $this->getTopData($periode, $jenisDataInflasi, 'inflasi_yoy', 'andil_yoy', $isYoYNegative);

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
            'jenisDataInflasi'
        ));
    }

    public function showInflasiSpasial(Request $request)
    {
        $jenisDataInflasi = $request->input('jenis_data_inflasi', 'ATAP');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $daftarKomoditasUtama = [
            'BERAS',
            'CABAI RAWIT',
            'CABAI MERAH',
            'BAWANG MERAH',
            'DAGING AYAM RAS',
            'TELUR AYAM RAS'
        ];
        $komoditasUtama = $request->input('komoditas_utama') ?? 'BERAS';
        $kabkota = $request->input('kabkota', '3500');
        $idWil = $kabkota ?: '3500'; // default provinsi
        $daftarKabKota = DB::table('master_wilayahs')
            ->where('kode_wil', '!=', 3500)
            ->orderBy('nama_wil')
            ->get();

        // Get all available periods for the filter dropdown
        $daftarPeriode = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('periode', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'bulan' => Carbon::parse($item->periode)->translatedFormat('F'),
                    'tahun' => Carbon::parse($item->periode)->format('Y'),
                ];
            });

        // If bulan and tahun are provided, use them
        if ($bulan && $tahun) {
            $bulanAngka = $this->bulanMap[$bulan] ?? null;
            if (!$bulanAngka) {
                // Try to convert English month to Indonesian
                $bulanIndo = Carbon::createFromFormat('F', $bulan)->translatedFormat('F');
                $bulanAngka = $this->bulanMap[$bulanIndo] ?? null;

                if (!$bulanAngka) {
                    abort(404, 'Bulan tidak valid');
                }
            }
            $periode = Carbon::createFromDate($tahun, $bulanAngka, 1)->startOfMonth();
        } else {
            // Otherwise, get the most recent data for the selected jenis_data_inflasi
            $latestData = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
                ->orderBy('periode', 'desc')
                ->first();

            if (!$latestData) {
                abort(404, 'Data tidak ditemukan untuk jenis data inflasi yang dipilih.');
            }

            $periode = Carbon::parse($latestData->periode);
        }

        // Get bulan and tahun from the selected period
        $bulan = $periode->translatedFormat('F');
        $tahun = $periode->format('Y');

        $rankingKabKota = [];
        if ($komoditasUtama && $periode) {
            $rankingKabKota = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
                ->join('master_wilayahs as mw', 'detail_inflasis.id_wil', '=', 'mw.kode_wil')
                ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
                ->where('master_inflasis.periode', $periode)
                ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
                ->where('detail_inflasis.id_flag', 3)
                ->where('mk.nama_kom', $komoditasUtama)
                ->orderByDesc('detail_inflasis.andil_mtm')
                ->select('mw.nama_wil', 'detail_inflasis.andil_mtm', 'detail_inflasis.inflasi_mtm')
                ->get();
        }

        // Get the highest and lowest contributing commodities
        $komoditasTertinggi = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderByDesc('detail_inflasis.andil_mtm')
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm')
            ->first();

        $komoditasTerendah = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('detail_inflasis.andil_mtm')
            ->select('mk.nama_kom', 'detail_inflasis.andil_mtm')
            ->first();

        $namaKomoditasTertinggi = optional($komoditasTertinggi)->nama_kom ?? '-';
        $andilTertinggi = optional($komoditasTertinggi)->andil_mtm ?? 0;

        $namaKomoditasTerendah = optional($komoditasTerendah)->nama_kom ?? '-';
        $andilTerendah = optional($komoditasTerendah)->andil_mtm ?? 0;

        // Get inflation values
        $inflasiMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->value('detail_inflasis.inflasi_mtm');

        $inflasiYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->value('detail_inflasis.inflasi_ytd');

        $inflasiYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->value('detail_inflasis.inflasi_yoy');

        $isMtMNegative = $inflasiMtM < 0;
        $isYtDNegative = $inflasiYtD < 0;
        $isYoYNegative = $inflasiYoY < 0;

        // Ambil data top inflasi
        $topInflasiMtM = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_mtm', 'andil_mtm', $isMtMNegative);
        $topInflasiYtD = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_ytd', 'andil_ytd', $isYtDNegative, $idWil);
        $topInflasiYoY = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_yoy', 'andil_yoy', $isYoYNegative, $idWil);

        // Ambil data top andil
        $topAndilMtM = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_mtm', 'andil_mtm', $isMtMNegative, $idWil);
        $topAndilYtD = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_ytd', 'andil_ytd', $isYtDNegative, $idWil);
        $topAndilYoY = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_yoy', 'andil_yoy', $isYoYNegative, $idWil);

        // Ambil data wilayah untuk map
        $wilayahs = DB::table('master_wilayahs')->get();

        $inflasiWilayah = \App\Models\detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_wilayahs as mw', 'detail_inflasis.id_wil', '=', 'mw.kode_wil')
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->where('detail_inflasis.id_flag', 0)
            ->select('mw.kode_wil', 'mw.nama_wil', 'detail_inflasis.inflasi_mtm')
            ->get();

        $inflasiKabKota = \App\Models\detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_wilayahs as mw', 'detail_inflasis.id_wil', '=', 'mw.kode_wil')
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', '!=', 3500)
            ->select('mw.kode_wil', 'mw.nama_wil', 'detail_inflasis.inflasi_mtm')
            ->get();

        // Hitung jumlah inflasi dan deflasi
        $jumlahInflasi = $inflasiKabKota->where('inflasi_mtm', '>', 0)->count();
        $jumlahDeflasi = $inflasiKabKota->where('inflasi_mtm', '<', 0)->count();

        // Ranking inflasi: dari yang paling minus ke mendekati nol (inflasi > 0)
        $rankingInflasi = $inflasiKabKota->where('inflasi_mtm', '>', 0)->sortByDesc('inflasi_mtm')->values();

        // Ranking deflasi: dari yang paling tinggi (paling minus) ke mendekati nol (inflasi < 0)
        $rankingDeflasi = $inflasiKabKota->where('inflasi_mtm', '<', 0)->sortBy('inflasi_mtm')->values();

        return view('dashboard.infSpasial', compact(
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
            'jenisDataInflasi',
            'wilayahs',
            'daftarKomoditasUtama',
            'komoditasUtama',
            'rankingKabKota',
            'daftarKabKota',
            'idWil',
            'kabkota',
            'inflasiWilayah',
            'jumlahInflasi',
            'jumlahDeflasi',
            'rankingInflasi',
            'rankingDeflasi',
        ));
    }

    public function showInflasiKelompok(Request $request)
    {
        $jenisDataInflasi = $request->input('jenis_data_inflasi', 'ATAP');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $kabkota = $request->input('kabkota', '3500');
        $idWil = $kabkota ?: '3500';

        // Daftar kab/kota untuk filter
        $daftarKabKota = DB::table('master_wilayahs')
            ->where('kode_wil', '!=', 3500)
            ->orderBy('nama_wil')
            ->get();

        // Daftar periode untuk filter
        $daftarPeriode = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('periode', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'bulan' => Carbon::parse($item->periode)->translatedFormat('F'),
                    'tahun' => Carbon::parse($item->periode)->format('Y'),
                ];
            });

        // Pilih periode
        if ($bulan && $tahun) {
            $bulanAngka = $this->bulanMap[$bulan] ?? null;
            if (!$bulanAngka) {
                $bulanIndo = Carbon::createFromFormat('F', $bulan)->translatedFormat('F');
                $bulanAngka = $this->bulanMap[$bulanIndo] ?? null;
                if (!$bulanAngka)
                    abort(404, 'Bulan tidak valid');
            }
            $periode = Carbon::createFromDate($tahun, $bulanAngka, 1)->startOfMonth();
        } else {
            $latestData = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
                ->orderBy('periode', 'desc')
                ->first();
            if (!$latestData)
                abort(404, 'Data tidak ditemukan.');
            $periode = Carbon::parse($latestData->periode);
        }
        $bulan = $periode->translatedFormat('F');
        $tahun = $periode->format('Y');

        // Ambil data kelompok pengeluaran (bukan komoditas)
        $topKelompokMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 2) // 2 = kelompok pengeluaran
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderByDesc('detail_inflasis.andil_mtm')
            ->select('mk.nama_kom as nama_kelompok', 'detail_inflasis.inflasi_mtm as inflasi', 'detail_inflasis.andil_mtm as andil')
            ->take(10)
            ->get();
        $topKelompokYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 2)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderByDesc('detail_inflasis.andil_ytd')
            ->select('mk.nama_kom as nama_kelompok', 'detail_inflasis.inflasi_ytd as inflasi', 'detail_inflasis.andil_ytd as andil')
            ->take(10)
            ->get();
        $topKelompokYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 2)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderByDesc('detail_inflasis.andil_yoy')
            ->select('mk.nama_kom as nama_kelompok', 'detail_inflasis.inflasi_yoy as inflasi', 'detail_inflasis.andil_yoy as andil')
            ->take(10)
            ->get();

        // Nilai inflasi utama
        $inflasiMtM = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->value('detail_inflasis.inflasi_mtm');
        $inflasiYtD = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->value('detail_inflasis.inflasi_ytd');
        $inflasiYoY = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->where('detail_inflasis.id_flag', 0)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->value('detail_inflasis.inflasi_yoy');

        // Ambil semua kelompok utama (flag 1)
        $kelompokUtama = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 1)
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('mk.kode_kom')
            ->select(
                'mk.kode_kom',
                'mk.nama_kom',
                'detail_inflasis.inflasi_mtm',
                'detail_inflasis.andil_mtm',
                'detail_inflasis.inflasi_ytd',
                'detail_inflasis.andil_ytd',
                'detail_inflasis.inflasi_yoy',
                'detail_inflasis.andil_yoy'
            )
            ->get();

        // Untuk setiap kelompok utama, ambil top 5 komoditas (flag 3) berdasarkan andil mtm
        $top5KomoditasPerKelompok = [];
        foreach ($kelompokUtama as $kelompok) {
            $top5KomoditasPerKelompok[$kelompok->kode_kom] = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
                ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
                ->where('detail_inflasis.id_flag', 3)
                ->where('detail_inflasis.id_wil', $idWil)
                ->where('master_inflasis.periode', $periode)
                ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
                ->where('mk.kode_kom', 'like', $kelompok->kode_kom . '%')
                ->orderByDesc('detail_inflasis.andil_mtm')
                ->select(
                    'mk.nama_kom',
                    'detail_inflasis.inflasi_mtm',
                    'detail_inflasis.andil_mtm',
                    'detail_inflasis.inflasi_ytd',
                    'detail_inflasis.andil_ytd',
                    'detail_inflasis.inflasi_yoy',
                    'detail_inflasis.andil_yoy'
                )
                ->take(5)
                ->get();
        }

        // Data tabel kelompok: flag 0,1,2
        $tabelKelompok = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->whereIn('detail_inflasis.id_flag', [0, 1, 2])
            ->where('detail_inflasis.id_wil', $idWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('mk.kode_kom')
            ->select(
                'mk.kode_kom',
                'mk.nama_kom',
                'detail_inflasis.inflasi_mtm',
                'detail_inflasis.andil_mtm',
                'detail_inflasis.inflasi_ytd',
                'detail_inflasis.andil_ytd',
                'detail_inflasis.inflasi_yoy',
                'detail_inflasis.andil_yoy'
            )
            ->get();

        return view('dashboard.infKelompok', compact(
            'bulan',
            'tahun',
            'daftarPeriode',
            'jenisDataInflasi',
            'topKelompokMtM',
            'topKelompokYtD',
            'topKelompokYoY',
            'inflasiMtM',
            'inflasiYtD',
            'inflasiYoY',
            'daftarKabKota',
            'kabkota',
            'tabelKelompok',
            'kelompokUtama',
            'top5KomoditasPerKelompok'
        ));
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        $jenisDataInflasi = $request->query('jenis_data_inflasi', 'ASEM1');

        $bulanAngka = $this->bulanMap[$bulan] ?? null;
        if (!$bulanAngka) {
            return response()->json(['error' => 'Bulan tidak valid'], 400);
        }

        // Ambil data dengan memanggil showInflasiBulanan
        $request->merge(['bulan' => $bulan, 'tahun' => $tahun, 'jenis_data_inflasi' => $jenisDataInflasi]);
        $data = $this->showInflasiBulanan($request)->getData();

        // Ambil nama file dari data yang sama dengan yang digunakan di dashboard
        $periode = master_inflasi::whereYear('periode', $tahun)
            ->whereMonth('periode', $bulanAngka)
            ->where('jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('periode', 'desc')
            ->first();

        if (!$periode) {
            $periode = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
                ->orderByRaw('ABS(DATEDIFF(periode, ?))', [Carbon::now()])
                ->first();
        }

        $namaFile = $periode ? $periode->nama : "Data Inflasi {$bulan} {$tahun} ({$jenisDataInflasi})";

        // Ambil data dari hasil showInflasiBulanan
        $dataMtM = $data['topInflasiMtM'];
        $dataYtD = $data['topInflasiYtD'];
        $dataYoY = $data['topInflasiYoY'];

        // Tambahkan nomor urut
        $dataMtM = $dataMtM->map(function ($item, $key) {
            $item->no = $key + 1;
            return $item;
        });

        $dataYtD = $dataYtD->map(function ($item, $key) {
            $item->no = $key + 1;
            return $item;
        });

        $dataYoY = $dataYoY->map(function ($item, $key) {
            $item->no = $key + 1;
            return $item;
        });

        // Buat spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Sheet 1: MtM
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Inflasi MtM');
        $sheet1->setCellValue('A1', 'No');
        $sheet1->setCellValue('B1', 'Nama Komoditas');
        $sheet1->setCellValue('C1', 'Inflasi MtM (%)');
        $sheet1->setCellValue('D1', 'Andil MtM (%)');

        $row = 2;
        foreach ($dataMtM as $item) {
            $sheet1->setCellValue('A' . $row, $item->no);
            $sheet1->setCellValue('B' . $row, $item->nama_kom);
            $sheet1->setCellValue('C' . $row, $item->inflasi);
            $sheet1->setCellValue('D' . $row, $item->andil);
            $row++;
        }

        // Sheet 2: YtD
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Inflasi YtD');
        $sheet2->setCellValue('A1', 'No');
        $sheet2->setCellValue('B1', 'Nama Komoditas');
        $sheet2->setCellValue('C1', 'Inflasi YtD (%)');
        $sheet2->setCellValue('D1', 'Andil YtD (%)');

        $row = 2;
        foreach ($dataYtD as $item) {
            $sheet2->setCellValue('A' . $row, $item->no);
            $sheet2->setCellValue('B' . $row, $item->nama_kom);
            $sheet2->setCellValue('C' . $row, $item->inflasi);
            $sheet2->setCellValue('D' . $row, $item->andil);
            $row++;
        }

        // Sheet 3: YoY
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Inflasi YoY');
        $sheet3->setCellValue('A1', 'No');
        $sheet3->setCellValue('B1', 'Nama Komoditas');
        $sheet3->setCellValue('C1', 'Inflasi YoY (%)');
        $sheet3->setCellValue('D1', 'Andil YoY (%)');

        $row = 2;
        foreach ($dataYoY as $item) {
            $sheet3->setCellValue('A' . $row, $item->no);
            $sheet3->setCellValue('B' . $row, $item->nama_kom);
            $sheet3->setCellValue('C' . $row, $item->inflasi);
            $sheet3->setCellValue('D' . $row, $item->andil);
            $row++;
        }

        // Set style untuk semua sheet
        foreach ($spreadsheet->getAllSheets() as $sheet) {
            // Set header style
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);
            $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(40);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);

            // Set number format for percentage columns with comma as decimal separator
            $sheet->getStyle('C2:D' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0.00');
        }

        // Buat writer dan download
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "10 Komoditas Tertinggi {$namaFile}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
