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
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::check()) {
            $jenisDataInflasi = 'ATAP';
        }

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
        if (!Auth::check()) {
            $jenisDataInflasi = 'ATAP';
        }
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        // Ambil komoditas utama dari tabel master_kom_utama
        $daftarKomoditasUtama = \App\Models\MasterKomUtama::orderBy('nama_kom')->pluck('nama_kom')->toArray();
        $komoditasUtama = $request->input('komoditas_utama') ?? ($daftarKomoditasUtama[0] ?? null);
        $user = Auth::user();
        $isKabkot = $user && $user->id_role == 2;
        $isAsem = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
        // Filter kabkota for admin kabkot and ASEM
        if ($isKabkot && $isAsem) {
            $daftarKabKota = collect([
                DB::table('master_wilayahs')->where('kode_wil', $user->id_satker)->first()
            ]);
            $kabkota = $request->input('kabkota', $user->id_satker);
        } else {
            $daftarKabKota = DB::table('master_wilayahs')
                ->where('kode_wil', '!=', 3500)
                ->orderBy('nama_wil')
                ->get();
            $kabkota = $request->input('kabkota', '3500');
        }
        $idWil = $kabkota ?: '3500'; // default provinsi

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
                ->select('mw.nama_wil', 'mw.kode_wil', 'detail_inflasis.andil_mtm', 'detail_inflasis.inflasi_mtm')
                ->get();
        }

        // Ambil data inflasi mtm dari semua komoditas utama pada kota teratas
        $inflasiKomoditasKotaTeratas = collect();
        if ($rankingKabKota->count() > 0) {
            $kotaTeratas = $rankingKabKota->first();
            $kodeWilKotaTeratas = $kotaTeratas->kode_wil;
            $inflasiKomoditasKotaTeratas = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
                ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
                ->where('detail_inflasis.id_flag', 3)
                ->where('detail_inflasis.id_wil', $kodeWilKotaTeratas)
                ->where('master_inflasis.periode', $periode)
                ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
                ->whereIn('mk.nama_kom', $daftarKomoditasUtama)
                ->select('mk.nama_kom', 'detail_inflasis.inflasi_mtm')
                ->orderByRaw("FIELD(mk.nama_kom, '" . implode("','", $daftarKomoditasUtama) . "')")
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

        $isMtMNegative = $inflasiMtM < 0;
        $isYtDNegative = $inflasiYtD < 0;
        $isYoYNegative = $inflasiYoY < 0;

        // Ambil data top inflasi
        $topInflasiMtM = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_mtm', 'andil_mtm', $isMtMNegative);
        $topInflasiYtD = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_ytd', 'andil_ytd', $isYtDNegative);
        $topInflasiYoY = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_yoy', 'andil_yoy', $isYoYNegative);

        // Ambil data top andil
        $topAndilMtM = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_mtm', 'andil_mtm', $isMtMNegative);
        $topAndilYtD = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_ytd', 'andil_ytd', $isYtDNegative);
        $topAndilYoY = $this->getTopDataSpasial($idWil, $periode, $jenisDataInflasi, 'inflasi_yoy', 'andil_yoy', $isYoYNegative);

        // Ambil data wilayah untuk map
        $wilayahs = DB::table('master_wilayahs')->get();

        $inflasiWilayah = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_wilayahs as mw', 'detail_inflasis.id_wil', '=', 'mw.kode_wil')
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->where('detail_inflasis.id_flag', 0)
            ->select('mw.kode_wil', 'mw.nama_wil', 'detail_inflasis.inflasi_mtm')
            ->get();

        $inflasiKabKota = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
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

        // Ambil semua komoditas
        $daftarSemuaKomoditas = \App\Models\master_komoditas::orderBy('nama_kom')->pluck('nama_kom')->toArray();

        // Hitung min/max untuk tabel dan chart
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
        $minAndilKab = $rankingKabKota->min('andil_mtm');
        $maxAndilKab = $rankingKabKota->max('andil_mtm');
        $minInflasiKab = $rankingKabKota->min('inflasi_mtm');
        $maxInflasiKab = $rankingKabKota->max('inflasi_mtm');
        // Flag role dan mode
        $isBlackWhite = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
        $isAdminProv = $user && $user->id_role == 1;
        $isAsem = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
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
            'inflasiKomoditasKotaTeratas',
            'daftarSemuaKomoditas',
            'minInflasiMtM', 'maxInflasiMtM',
            'minInflasiYtD', 'maxInflasiYtD',
            'minInflasiYoY', 'maxInflasiYoY',
            'minAndilMtM', 'maxAndilMtM',
            'minAndilYtD', 'maxAndilYtD',
            'minAndilYoY', 'maxAndilYoY',
            'minAndilKab', 'maxAndilKab',
            'minInflasiKab', 'maxInflasiKab',
            'isBlackWhite', 'isAdminProv', 'isAsem', 'user'
        ));
    }

    public function showInflasiKelompok(Request $request)
    {
        $jenisDataInflasi = $request->input('jenis_data_inflasi', 'ATAP');
        if (!Auth::check()) {
            $jenisDataInflasi = 'ATAP';
        }
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $user = Auth::user();
        $isKabkot = $user && $user->id_role == 2;
        $isAsem = in_array($jenisDataInflasi, ['ASEM 1', 'ASEM 2', 'ASEM 3']);
        // Filter kabkota for admin kabkot and ASEM
        if ($isKabkot && $isAsem) {
            $daftarKabKota = collect([
                DB::table('master_wilayahs')->where('kode_wil', $user->id_satker)->first()
            ]);
            $kabkota = $request->input('kabkota', $user->id_satker);
        } else {
            $daftarKabKota = DB::table('master_wilayahs')
                ->where('kode_wil', '!=', 3500)
                ->orderBy('nama_wil')
                ->get();
            $kabkota = $request->input('kabkota', '3500');
        }
        $idWil = $kabkota ?: '3500';

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
            ->whereIn('detail_inflasis.id_flag', [0, 1])
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

    public function showSeriesInflasi(Request $request)
    {
        $jenisDataInflasi = $request->input('jenis_data_inflasi', 'ATAP');
        if (!Auth::check()) {
            $jenisDataInflasi = 'ATAP';
        }
        // Ambil komoditas utama dari tabel master_kom_utama
        $daftarKomoditasUtama = \App\Models\MasterKomUtama::orderBy('nama_kom')->pluck('nama_kom')->prepend('UMUM')->toArray();
        $komoditas = $request->input('komoditas', 'UMUM');
        $tahun = $request->input('tahun', now()->year);

        // Ambil semua periode (bulan) yang tersedia untuk tahun & komoditas ini
        $periodeList = master_inflasi::whereYear('periode', $tahun)
            ->orderBy('periode')
            ->pluck('periode');

        $bulanList = $periodeList->map(function ($periode) {
            return Carbon::parse($periode)->translatedFormat('F');
        });

        // List tahun tersedia (khusus jenis inflasi terpilih)
        $tahunList = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
            ->selectRaw('YEAR(periode) as tahun')->distinct()->orderBy('tahun')->pluck('tahun')->toArray();

        // Default tahun: tahun terdekat dengan now dari $tahunList
        if (!$request->filled('tahun')) {
            $nowYear = now()->year;
            $tahun = collect($tahunList)->sortBy(function($th) use ($nowYear) {
                return abs($th - $nowYear);
            })->first();
        } else {
            $tahun = $request->input('tahun');
        }

        // Ambil data series per bulan untuk komoditas ini
        $filterPeriode = null;
        if ($request->filled('tahun_bulan')) {
            $filterPeriode = collect($request->input('tahun_bulan'))
                ->map(function($val) {
                    [$th, $bln] = explode('-', $val, 2);
                    // Konversi nama bulan ke angka
                    $bulanMap = [
                        'Januari'=>1,'February'=>2,'Maret'=>3,'April'=>4,'Mei'=>5,'Juni'=>6,'Juli'=>7,'Agustus'=>8,'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12,
                        'January'=>1,'Februari'=>2,'March'=>3,'May'=>5,'June'=>6,'July'=>7,'August'=>8,'October'=>10,'December'=>12
                    ];
                    $blnNum = $bulanMap[$bln] ?? null;
                    return $blnNum && $th ? [$th, $blnNum] : null;
                })
                ->filter();
        }
        if ($komoditas === 'UMUM') {
            $query = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
                ->where('detail_inflasis.id_flag', 0)
                ->where('detail_inflasis.id_wil', 3500)
                ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi);
            if ($filterPeriode && $filterPeriode->count()) {
                $query->where(function($q) use ($filterPeriode) {
                    foreach ($filterPeriode as [$th, $blnNum]) {
                        $q->orWhere(function($sub) use ($th, $blnNum) {
                            $sub->whereYear('master_inflasis.periode', $th)
                                ->whereMonth('master_inflasis.periode', $blnNum);
                        });
                    }
                });
            } else {
                $query->whereYear('master_inflasis.periode', $tahun);
            }
            $data = $query->orderBy('master_inflasis.periode')
                ->select(
                    'master_inflasis.periode',
                    'detail_inflasis.inflasi_mtm',
                    'detail_inflasis.andil_mtm',
                    'detail_inflasis.inflasi_ytd',
                    'detail_inflasis.andil_ytd',
                    'detail_inflasis.inflasi_yoy',
                    'detail_inflasis.andil_yoy'
                )
                ->get();
        } else {
            $query = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
                ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
                ->where('detail_inflasis.id_flag', 3)
                ->where('detail_inflasis.id_wil', 3500)
                ->where('mk.nama_kom', $komoditas)
                ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi);
            if ($filterPeriode && $filterPeriode->count()) {
                $query->where(function($q) use ($filterPeriode) {
                    foreach ($filterPeriode as [$th, $blnNum]) {
                        $q->orWhere(function($sub) use ($th, $blnNum) {
                            $sub->whereYear('master_inflasis.periode', $th)
                                ->whereMonth('master_inflasis.periode', $blnNum);
                        });
                    }
                });
            } else {
                $query->whereYear('master_inflasis.periode', $tahun);
            }
            $data = $query->orderBy('master_inflasis.periode')
                ->select(
                    'master_inflasis.periode',
                    'detail_inflasis.inflasi_mtm',
                    'detail_inflasis.andil_mtm',
                    'detail_inflasis.inflasi_ytd',
                    'detail_inflasis.andil_ytd',
                    'detail_inflasis.inflasi_yoy',
                    'detail_inflasis.andil_yoy'
                )
                ->get();
        }

        // Format data untuk chart
        $seriesData = [
            'bulan' => [],
            'inflasi_mtm' => [],
            'andil_mtm' => [],
            'inflasi_ytd' => [],
            'andil_ytd' => [],
            'inflasi_yoy' => [],
            'andil_yoy' => [],
        ];
        foreach ($data as $row) {
            $seriesData['bulan'][] = Carbon::parse($row->periode)->translatedFormat('F');
            $seriesData['inflasi_mtm'][] = $row->inflasi_mtm;
            $seriesData['andil_mtm'][] = $row->andil_mtm;
            $seriesData['inflasi_ytd'][] = $row->inflasi_ytd;
            $seriesData['andil_ytd'][] = $row->andil_ytd;
            $seriesData['inflasi_yoy'][] = $row->inflasi_yoy;
            $seriesData['andil_yoy'][] = $row->andil_yoy;
        }

        // List tahun tersedia (khusus jenis inflasi terpilih)
        $tahunList = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
            ->selectRaw('YEAR(periode) as tahun')->distinct()->orderBy('tahun')->pluck('tahun')->toArray();

        // Default tahun: tahun terdekat dengan now dari $tahunList
        if (!$request->filled('tahun')) {
            $nowYear = now()->year;
            $tahun = collect($tahunList)->sortBy(function($th) use ($nowYear) {
                return abs($th - $nowYear);
            })->first();
        } else {
            $tahun = $request->input('tahun');
        }

        // Ambil data series per bulan untuk komoditas ini
        $filterPeriode = null;
        if ($request->filled('tahun_bulan')) {
            $filterPeriode = collect($request->input('tahun_bulan'))
                ->map(function($val) {
                    [$th, $bln] = explode('-', $val, 2);
                    // Konversi nama bulan ke angka
                    $bulanMap = [
                        'Januari'=>1,'February'=>2,'Maret'=>3,'April'=>4,'Mei'=>5,'Juni'=>6,'Juli'=>7,'Agustus'=>8,'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12,
                        'January'=>1,'Februari'=>2,'March'=>3,'May'=>5,'June'=>6,'July'=>7,'August'=>8,'October'=>10,'December'=>12
                    ];
                    $blnNum = $bulanMap[$bln] ?? null;
                    return $blnNum && $th ? [$th, $blnNum] : null;
                })
                ->filter();
        }

        // Ambil semua periode yang tersedia untuk jenis inflasi ini
        $periodeData = master_inflasi::where('jenis_data_inflasi', $jenisDataInflasi)
            ->orderBy('periode')
            ->pluck('periode');

        // Bangun struktur tahun-bulan
        $tahunBulanList = [];
        foreach ($periodeData as $periode) {
            $carbon = Carbon::parse($periode);
            $tahun = $carbon->format('Y');
            $bulan = $carbon->translatedFormat('F');
            $tahunBulanList[$tahun][] = $bulan;
        }

        return view('dashboard.infseries', compact(
            'daftarKomoditasUtama',
            'komoditas',
            'jenisDataInflasi',
            'tahun',
            'tahunList',
            'bulanList',
            'seriesData',
            'tahunBulanList'
        ));
    }

    public function exportExcel(Request $request)
    {
        if ($request->query('spasial') === '1') {
            return $this->exportExcelSpasial($request);
        }
        if ($request->query('kelompok') === '1') {
            return $this->exportExcelKelompok($request);
        }
        if ($request->query('series') === '1') {
            return $this->exportExcelSeries($request);
        }
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

    /**
     * Export Excel khusus untuk dashboard Spasial (5 sheet)
     */
    public function exportExcelSpasial(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        $jenisDataInflasi = $request->query('jenis_data_inflasi', 'ATAP');
        $komoditasUtama = $request->query('komoditas_utama');
        $kabkota = $request->query('kabkota');

        $bulanAngka = $this->bulanMap[$bulan] ?? null;
        if (!$bulanAngka) {
            return response()->json(['error' => 'Bulan tidak valid'], 400);
        }

        // Prepare a fake request to get the same data as the dashboard
        $req = new Request([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jenis_data_inflasi' => $jenisDataInflasi,
            'komoditas_utama' => $komoditasUtama,
            'kabkota' => $kabkota,
        ]);
        $data = $this->showInflasiSpasial($req)->getData();

        // Sheet 1: Combined Inflasi & Deflasi
        $rankingInflasi = $data['rankingInflasi'];
        $rankingDeflasi = $data['rankingDeflasi'];
        $maxRows = max(count($rankingInflasi), count($rankingDeflasi));

        // Sheet 2: Peringkat Kab/Kota Menurut Komoditas Utama
        $rankingKabKota = $data['rankingKabKota'];
        // Sheet 3: 10 Komoditas Teratas MtM
        $topInflasiMtM = $data['topInflasiMtM'];
        // Sheet 4: 10 Komoditas Teratas YtD
        $topInflasiYtD = $data['topInflasiYtD'];
        // Sheet 5: 10 Komoditas Teratas YoY
        $topInflasiYoY = $data['topInflasiYoY'];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Sheet 1: Combined Inflasi & Deflasi
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Peringkat Kab-Kota');
        $sheet1->setCellValue('A1', 'No');
        $sheet1->setCellValue('B1', 'Kab/Kota Inflasi');
        $sheet1->setCellValue('C1', 'Andil Inflasi');
        $sheet1->setCellValue('D1', 'Inflasi');
        $sheet1->setCellValue('E1', 'No');
        $sheet1->setCellValue('F1', 'Kab/Kota Deflasi');
        $sheet1->setCellValue('G1', 'Andil Deflasi');
        $sheet1->setCellValue('H1', 'Deflasi');
        for ($i = 0; $i < $maxRows; $i++) {
            $row = $i + 2;
            // Inflasi
            if (isset($rankingInflasi[$i])) {
                $sheet1->setCellValue('A'.$row, $i+1);
                $sheet1->setCellValue('B'.$row, $rankingInflasi[$i]->nama_wil);
                $sheet1->setCellValue('C'.$row, $rankingInflasi[$i]->andil_mtm);
                $sheet1->setCellValue('D'.$row, $rankingInflasi[$i]->inflasi_mtm);
            }
            // Deflasi
            if (isset($rankingDeflasi[$i])) {
                $sheet1->setCellValue('E'.$row, $i+1);
                $sheet1->setCellValue('F'.$row, $rankingDeflasi[$i]->nama_wil);
                $sheet1->setCellValue('G'.$row, $rankingDeflasi[$i]->andil_mtm);
                $sheet1->setCellValue('H'.$row, $rankingDeflasi[$i]->inflasi_mtm);
            }
        }
        // Style
        $sheet1->getStyle('A1:H1')->getFont()->setBold(true);
        foreach (range('A', 'H') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet1->getStyle('C2:D'.($maxRows+1))->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet1->getStyle('G2:H'.($maxRows+1))->getNumberFormat()->setFormatCode('#,##0.00');

        // Sheet 2: Peringkat Kab/Kota Menurut Komoditas Utama
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Kab-Kota Kom Utama');
        $sheet2->setCellValue('A1', 'No');
        $sheet2->setCellValue('B1', 'Kab/Kota');
        $sheet2->setCellValue('C1', 'Andil');
        $sheet2->setCellValue('D1', 'Inflasi');
        foreach ($rankingKabKota as $i => $item) {
            $row = $i + 2;
            $sheet2->setCellValue('A'.$row, $i+1);
            $sheet2->setCellValue('B'.$row, $item->nama_wil);
            $sheet2->setCellValue('C'.$row, $item->andil_mtm);
            $sheet2->setCellValue('D'.$row, $item->inflasi_mtm);
        }
        $sheet2->getStyle('A1:D1')->getFont()->setBold(true);
        foreach (range('A', 'D') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet2->getStyle('C2:D'.(count($rankingKabKota)+1))->getNumberFormat()->setFormatCode('#,##0.00');

        // Helper for komoditas sheets
        $makeKomoditasSheet = function($sheet, $title, $data) {
            $sheet->setTitle($title);
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Komoditas');
            $sheet->setCellValue('C1', 'Andil');
            $sheet->setCellValue('D1', 'Inflasi');
            foreach ($data as $i => $item) {
                $row = $i + 2;
                $sheet->setCellValue('A'.$row, $i+1);
                $sheet->setCellValue('B'.$row, $item->nama_kom);
                $sheet->setCellValue('C'.$row, $item->andil);
                $sheet->setCellValue('D'.$row, $item->inflasi);
            }
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            $sheet->getStyle('C2:D'.(count($data)+1))->getNumberFormat()->setFormatCode('#,##0.00');
        };
        // Sheet 3: MtM
        $sheet3 = $spreadsheet->createSheet();
        $makeKomoditasSheet($sheet3, 'Top Komoditas MtM', $topInflasiMtM);
        // Sheet 4: YtD
        $sheet4 = $spreadsheet->createSheet();
        $makeKomoditasSheet($sheet4, 'Top Komoditas YtD', $topInflasiYtD);
        // Sheet 5: YoY
        $sheet5 = $spreadsheet->createSheet();
        $makeKomoditasSheet($sheet5, 'Top Komoditas YoY', $topInflasiYoY);

        // File name
        $filename = "Dashboard-Spasial-{$bulan}-{$tahun}-{$jenisDataInflasi}.xlsx";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    /**
     * Export Excel untuk dashboard Kelompok
     */
    public function exportExcelKelompok(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        $jenisDataInflasi = $request->query('jenis_data_inflasi', 'ATAP');
        $kabkota = $request->query('kabkota');
        $bulanAngka = $this->bulanMap[$bulan] ?? null;
        if (!$bulanAngka) {
            return response()->json(['error' => 'Bulan tidak valid'], 400);
        }
        $req = new Request([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jenis_data_inflasi' => $jenisDataInflasi,
            'kabkota' => $kabkota,
        ]);
        $data = $this->showInflasiKelompok($req)->getData();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Sheet 1: Peringkat Kelompok Pengeluaran Berdasarkan Nilai Andil
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Peringkat Kelompok');
        $sheet1->setCellValue('A1', 'No');
        $sheet1->setCellValue('B1', 'Kelompok');
        $sheet1->setCellValue('C1', 'Andil');
        $sheet1->setCellValue('D1', 'Inflasi');
        foreach ($data['topKelompokMtM'] as $i => $item) {
            $row = $i + 2;
            $sheet1->setCellValue('A'.$row, $i+1);
            $sheet1->setCellValue('B'.$row, $item->nama_kelompok);
            $sheet1->setCellValue('C'.$row, $item->andil);
            $sheet1->setCellValue('D'.$row, $item->inflasi);
        }
        $sheet1->getStyle('A1:D1')->getFont()->setBold(true);
        foreach (range('A', 'D') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet1->getStyle('C2:D'.(count($data['topKelompokMtM'])+1))->getNumberFormat()->setFormatCode('#,##0.00');
        // Sheet 2: Tabel Inflasi Bulanan Menurut Kelompok Pengeluaran
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Tabel Kelompok');
        $sheet2->setCellValue('A1', 'No');
        $sheet2->setCellValue('B1', 'Kelompok');
        $sheet2->setCellValue('C1', 'Inflasi MtM');
        $sheet2->setCellValue('D1', 'Andil MtM');
        $sheet2->setCellValue('E1', 'Inflasi YtD');
        $sheet2->setCellValue('F1', 'Andil YtD');
        $sheet2->setCellValue('G1', 'Inflasi YoY');
        $sheet2->setCellValue('H1', 'Andil YoY');
        foreach ($data['tabelKelompok'] as $i => $item) {
            $row = $i + 2;
            $sheet2->setCellValue('A'.$row, $i+1);
            $sheet2->setCellValue('B'.$row, $item->nama_kom);
            $sheet2->setCellValue('C'.$row, $item->inflasi_mtm);
            $sheet2->setCellValue('D'.$row, $item->andil_mtm);
            $sheet2->setCellValue('E'.$row, $item->inflasi_ytd);
            $sheet2->setCellValue('F'.$row, $item->andil_ytd);
            $sheet2->setCellValue('G'.$row, $item->inflasi_yoy);
            $sheet2->setCellValue('H'.$row, $item->andil_yoy);
        }
        $sheet2->getStyle('A1:H1')->getFont()->setBold(true);
        foreach (range('A', 'H') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet2->getStyle('C2:H'.(count($data['tabelKelompok'])+1))->getNumberFormat()->setFormatCode('#,##0.00');
        // Sheet 3: 5 Komoditas Teratas per Kelompok
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Top Komoditas Kelompok');
        $sheet3->setCellValue('A1', 'Kelompok');
        $sheet3->setCellValue('B1', 'No');
        $sheet3->setCellValue('C1', 'Komoditas');
        $sheet3->setCellValue('D1', 'Andil');
        $sheet3->setCellValue('E1', 'Inflasi');
        $row = 2;
        foreach ($data['top5KomoditasPerKelompok'] as $kelompokNama => $komoditasList) {
            foreach ($komoditasList as $i => $kom) {
                $sheet3->setCellValue('A'.$row, $kelompokNama);
                $sheet3->setCellValue('B'.$row, $i+1);
                $sheet3->setCellValue('C'.$row, $kom->nama_kom);
                $sheet3->setCellValue('D'.$row, $kom->andil_mtm);
                $sheet3->setCellValue('E'.$row, $kom->inflasi_mtm);
                $row++;
            }
        }
        $sheet3->getStyle('A1:E1')->getFont()->setBold(true);
        foreach (range('A', 'E') as $col) {
            $sheet3->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet3->getStyle('D2:E'.($row-1))->getNumberFormat()->setFormatCode('#,##0.00');
        // File name
        $filename = "Dashboard-Kelompok-{$bulan}-{$tahun}-{$jenisDataInflasi}.xlsx";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    /**
     * Export Excel untuk dashboard Series
     */
    public function exportExcelSeries(Request $request)
    {
        $jenisDataInflasi = $request->query('jenis_data_inflasi', 'ATAP');
        $komoditas = $request->query('komoditas');
        $tahun = $request->query('tahun');
        $tahun_check = $request->query('tahun_check', []);
        $tahun_bulan = $request->query('tahun_bulan', []);
        $req = new Request([
            'jenis_data_inflasi' => $jenisDataInflasi,
            'komoditas' => $komoditas,
            'tahun' => $tahun,
            'tahun_check' => $tahun_check,
            'tahun_bulan' => $tahun_bulan,
        ]);
        $data = $this->showSeriesInflasi($req)->getData();
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Helper for each sheet
        $makeSheet = function($sheet, $title, $periodeArr, $andilArr, $inflasiArr) {
            $sheet->setTitle($title);
            $sheet->setCellValue('A1', 'Periode Data');
            $sheet->setCellValue('B1', 'Andil');
            $sheet->setCellValue('C1', 'Inflasi');
            foreach ($periodeArr as $i => $periode) {
                $row = $i + 2;
                $sheet->setCellValue('A'.$row, $periode);
                $sheet->setCellValue('B'.$row, $andilArr[$i]);
                $sheet->setCellValue('C'.$row, $inflasiArr[$i]);
            }
            $sheet->getStyle('A1:C1')->getFont()->setBold(true);
            foreach (range('A', 'C') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            $sheet->getStyle('B2:C'.(count($periodeArr)+1))->getNumberFormat()->setFormatCode('#,##0.00');
        };
        // Sheet 1: MtM
        $sheet1 = $spreadsheet->getActiveSheet();
        $makeSheet($sheet1, 'Series MtM', $data['seriesData']['bulan'], $data['seriesData']['andil_mtm'], $data['seriesData']['inflasi_mtm']);
        // Sheet 2: YtD
        $sheet2 = $spreadsheet->createSheet();
        $makeSheet($sheet2, 'Series YtD', $data['seriesData']['bulan'], $data['seriesData']['andil_ytd'], $data['seriesData']['inflasi_ytd']);
        // Sheet 3: YoY
        $sheet3 = $spreadsheet->createSheet();
        $makeSheet($sheet3, 'Series YoY', $data['seriesData']['bulan'], $data['seriesData']['andil_yoy'], $data['seriesData']['inflasi_yoy']);
        // File name
        $filename = "Dashboard-Series-{$komoditas}-{$tahun}-{$jenisDataInflasi}.xlsx";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function getInflasiKomoditasKabKotaAjax(Request $request)
    {
        $kodeWil = $request->input('kode_wil');
        $jenisDataInflasi = $request->input('jenis_data_inflasi', 'ATAP');
        $periodeStr = $request->input('periode');
        // Ambil komoditas utama dari tabel master_kom_utama
        $daftarKomoditasUtama = \App\Models\MasterKomUtama::pluck('nama_kom')->toArray();
        // Parse periode string ("Bulan Tahun")
        $bulanTahun = explode(' ', $periodeStr);
        $bulan = $bulanTahun[0] ?? null;
        $tahun = $bulanTahun[1] ?? null;
        $bulanMap = $this->bulanMap;
        $bulanAngka = $bulanMap[$bulan] ?? null;
        if (!$bulanAngka) {
            // Try to convert English month to Indonesian
            try {
                $bulanIndo = Carbon::createFromFormat('F', $bulan)->translatedFormat('F');
                $bulanAngka = $bulanMap[$bulanIndo] ?? null;
            } catch (\Exception $e) {
                return response()->json([]);
            }
        }
        $periode = null;
        if ($bulanAngka && $tahun) {
            $periode = Carbon::createFromDate($tahun, $bulanAngka, 1)->startOfMonth();
        }
        if (!$periode) return response()->json([]);
        $data = detail_inflasi::join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_flag', 3)
            ->where('detail_inflasis.id_wil', $kodeWil)
            ->where('master_inflasis.periode', $periode)
            ->where('master_inflasis.jenis_data_inflasi', $jenisDataInflasi)
            ->whereIn('mk.nama_kom', $daftarKomoditasUtama)
            ->select('mk.nama_kom', 'detail_inflasis.inflasi_mtm')
            ->orderByRaw("FIELD(mk.nama_kom, '" . implode("','", $daftarKomoditasUtama) . "')")
            ->get();
        return response()->json($data);
    }

    public function tabelDinamisData(Request $request)
    {
        $wilayah = $request->input('wilayah', []); // array kode_wil
        $komoditas = $request->input('komoditas', []); // array nama_kom
        $periode = $request->input('periode'); // format: 'Januari 2025'
        $value = $request->input('value');

        // Mapping value ke kolom DB
        $valueMap = [
            'inf_mtm' => 'inflasi_MtM',
            'inf_ytd' => 'inflasi_YtD',
            'inf_yoy' => 'inflasi_YoY',
            'andil_mtm' => 'andil_MtM',
            'andil_ytd' => 'andil_YtD',
            'andil_yoy' => 'andil_YoY',
        ];
        $dbValue = $valueMap[$value] ?? null;
        if (!$dbValue) return response()->json([]);

        // Parse periode ke date
        [$bulan, $tahun] = explode(' ', $periode);
        $bulanMap = [
            'Januari' => 1, 'February' => 2, 'Februari' => 2, 'Maret' => 3, 'March' => 3, 'April' => 4, 'Mei' => 5, 'May' => 5, 'Juni' => 6, 'June' => 6, 'Juli' => 7, 'July' => 7, 'Agustus' => 8, 'August' => 8, 'September' => 9, 'Oktober' => 10, 'October' => 10, 'November' => 11, 'Desember' => 12, 'December' => 12
        ];
        $bulanAngka = $bulanMap[$bulan] ?? null;
        if (!$bulanAngka) return response()->json([]);
        $periodeDate = sprintf('%04d-%02d-01', $tahun, $bulanAngka);

        // Ambil id_kom dari nama_kom
        $komoditasRows = \App\Models\master_komoditas::whereIn('nama_kom', $komoditas)->pluck('kode_kom', 'nama_kom');
        // Ambil id_wil dari kode_wil
        $wilayahRows = \App\Models\master_wilayah::whereIn('kode_wil', $wilayah)->pluck('kode_wil');

        // Ambil id_inflasi dari master_inflasis
        $masterInflasi = \App\Models\master_inflasi::where('periode', $periodeDate)->first();
        if (!$masterInflasi) return response()->json([]);
        $idInflasi = $masterInflasi->id;

        // Query detail_inflasis
        $data = \App\Models\detail_inflasi::where('id_inflasi', $idInflasi)
            ->whereIn('id_wil', $wilayahRows)
            ->whereIn('id_kom', $komoditasRows)
            ->get();

        // Ambil mapping nama komoditas dan wilayah
        $namaKomoditas = \App\Models\master_komoditas::whereIn('kode_kom', $komoditasRows)->pluck('nama_kom', 'kode_kom');
        $namaWilayah = \App\Models\master_wilayah::whereIn('kode_wil', $wilayahRows)->pluck('nama_wil', 'kode_wil');

        $result = [];
        foreach ($wilayahRows as $wil) {
            $result[$wil] = [];
            foreach ($komoditasRows as $namakom => $kom) {
                $item = $data->first(function($row) use ($wil, $kom) {
                    return $row->id_wil == $wil && $row->id_kom == $kom;
                });
                $result[$wil][$namakom] = $item ? $item->{$dbValue} : null;
            }
        }

        // Hapus semua debug log sebelum return
        return response()->json($result);
    }
}
