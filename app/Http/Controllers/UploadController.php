<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\master_inflasi;
use App\Models\detail_inflasi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\master_wilayah;

class UploadController extends Controller
{
    //FUNGSI TAMPILIN, SEARCH, N PAGINATION
    public function landing(Request $request)
    {
        $search = $request->input('search');

        $uploads = master_inflasi::with('pengguna')
            ->where('jenis_data_inflasi', 'ATAP')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('jenis_data_inflasi', 'like', "%{$search}%");
                });
            })
            ->orderBy('periode', 'desc')
            ->paginate(10);

        // Format nama untuk tampilan
        $uploads->getCollection()->transform(function ($item) {
            $periode = Carbon::parse($item->periode);
            $item->display_name = 'Data Inflasi Provinsi Jawa Timur Menurut Kelompok Pengeluaran Bulan ' .
                $periode->locale('id')->translatedFormat('F Y');
            return $item;
        });

        return view('user.index', compact('uploads', 'search'));
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $uploads = master_inflasi::with('pengguna')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('jenis_data_inflasi', 'like', "%{$search}%");
            })
            ->orderBy('periode', 'desc')
            ->paginate(10);

        return view('prov.manajemen-data-inflasi.index', compact('uploads', 'search'));
    }

    public function create()
    {
        return view('prov.manajemen-data-inflasi.create');
    }

    private function truncateToTwoDecimals($number)
    {
        return $number < 0
            ? ceil($number * 100) / 100
            : floor($number * 100) / 100;
    }

    private function processInflasiValue($value)
    {
        if (isset($value) && is_numeric(trim($value))) {
            return $this->truncateToTwoDecimals(floatval(trim($value)));
        }
        return null;
    }

    public function uploadInflasiAjax(Request $request)
    {
        ini_set('memory_limit', '512M'); // Tambahkan baris ini untuk menaikkan limit memori

        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'jenis_data_inflasi' => 'required|in:ASEM 1,ASEM 2,ASEM 3,ATAP',
            'file' => 'required|mimes:xlsx'
        ]);

        $rawPeriode = trim($request->periode);
        [$tahun, $bulan] = explode('-', $rawPeriode);

        if (!checkdate($bulan, 1, $tahun)) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tidak valid.',
            ], 422);
        }
        // Format periode menjadi YYYY-MM-DD
        $periode = Carbon::createFromDate($tahun, $bulan, 1)->toDateString();

        $jenisDataInflasi = $request->jenis_data_inflasi;

        $existingData = master_inflasi::where('periode', $periode)
            ->where('jenis_data_inflasi', $jenisDataInflasi)
            ->exists();

        if ($existingData) {
            return response()->json([
                'success' => false,
                'message' => 'Data untuk periode dan jenis data inflasi terpilih sudah ada. Silakan pilih data lain.',
                'errors' => [
                    'periode' => 'Data untuk periode dan jenis data inflasi terpilih sudah ada.'
                ]
            ], 422);
        }

        $carbonPeriode = Carbon::parse($request->periode . '-01', 'Asia/Jakarta')
            ->startOfMonth();

        $nama = 'Data Inflasi ' . $jenisDataInflasi . ' ' .
            $carbonPeriode
            ->locale('id')
            ->translatedFormat('F Y');

        DB::beginTransaction();
        try {

            $dataInflasi = master_inflasi::create([
                'id_pengguna' => Auth::user()->id,
                'nama' => $nama,
                'periode' => $periode,
                'jenis_data_inflasi' => $jenisDataInflasi,
                'upload_at' => now(),
            ]);

            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);

            // Hapus baris kosong di akhir file (trailing empty rows)
            for ($i = count($rows) - 1; $i >= 0; $i--) {
                if (!array_filter($rows[$i])) {
                    unset($rows[$i]);
                } else {
                    break;
                }
            }
            $rows = array_values($rows); // reindex array

            $header = array_shift($rows);

            // Penyesuaian header dan mapping kolom berdasarkan jenis data
            $map = [];
            $indexes = [];
            if (str_starts_with($jenisDataInflasi, 'ASEM')) {
                $requiredColumns = [
                    'Tahun',
                    'Bulan',
                    'Kd.Kota',
                    'Nama Kota',
                    'Kode',
                    'Nama',
                    'Flag',
                    'IHK',
                    'INF(MOM)',
                    'INF(YTD)',
                    'INF(YOY)',
                    'ANDIL(MOM)',
                    'ANDIL(YTD)',
                    'ANDIL(YOY)'
                ];
                $indexes = array_map(fn($col) => array_search($col, $header), $requiredColumns);
                $map = [
                    'kode_kota' => $indexes[2], // Kd.Kota
                    'kode_komoditas' => $indexes[4], // Kode
                    'flag' => $indexes[6], // Flag
                    'inflasi_MtM' => $indexes[8], // INF(MOM)
                    'inflasi_YtD' => $indexes[9], // INF(YTD)
                    'inflasi_YoY' => $indexes[10], // INF(YOY)
                    'andil_MtM' => $indexes[11], // ANDIL(MOM)
                    'andil_YtD' => $indexes[12], // ANDIL(YTD)
                    'andil_YoY' => $indexes[13], // ANDIL(YOY)
                ];
            } else { // ATAP
                $requiredColumns = ['Kode Kota', 'Kode Komoditas', 'Flag', 'Inflasi MtM', 'Inflasi YtD', 'Inflasi YoY', 'Andil MtM', 'Andil YtD', 'Andil YoY'];
                $indexes = array_map(fn($col) => array_search($col, $header), $requiredColumns);
                $map = [
                    'kode_kota' => $indexes[0],
                    'kode_komoditas' => $indexes[1],
                    'flag' => $indexes[2],
                    'inflasi_MtM' => $indexes[3],
                    'inflasi_YtD' => $indexes[4],
                    'inflasi_YoY' => $indexes[5],
                    'andil_MtM' => $indexes[6],
                    'andil_YtD' => $indexes[7],
                    'andil_YoY' => $indexes[8],
                ];
            }

            if (in_array(false, $indexes, true)) {
                $missingColumns = [];
                foreach ($requiredColumns as $i => $col) {
                    if ($indexes[$i] === false) {
                        $missingColumns[] = $col;
                    }
                }
                $msg = 'Format file tidak sesuai. Kolom berikut kurang: ' . implode(', ', $missingColumns) . '. Pastikan file memiliki semua kolom yang diperlukan.';
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'errors' => $missingColumns,
                ], 422);
            }

            Log::info('Header:', $header);
            Log::info('Indexes:', $indexes);
            Log::info('Rows:', $rows);

            // --- AUTO-FIX DAN VALIDASI KODE KOMODITAS & WILAYAH ---
            $errorRows = [];
            $rowCount = count($rows);
            $dataToInsert = collect($rows)
                ->map(function ($row, $rowIndex) use ($dataInflasi, $map, $rows, $rowCount, $header, $jenisDataInflasi, &$errorRows) {
                    // Skip baris kosong di tengah data (hanya error jika setelahnya masih ada data)
                    if (!array_filter($row)) {
                        if (($rowIndex + 1 < $rowCount) && array_filter($rows[$rowIndex + 1])) {
                            $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": baris kosong.";
                        }
                        return null;
                    }

                    // --- Ambil kode kota dan komoditas ---
                    $kodeKota = trim($row[$map['kode_kota']] ?? '');
                    $kodeKomoditas = $row[$map['kode_komoditas']] ?? '';
                    $flagKomoditas = $row[$map['flag']] ?? '';

                    // --- Auto-fix kode kota ---
                    if (str_starts_with($jenisDataInflasi, 'ASEM')) {
                        if ($kodeKota === '35') {
                            $kodeKota = '3500';
                        }
                    } else {
                        if (is_numeric($kodeKota) && $kodeKota < 1000) {
                            $kodeKota = str_pad($kodeKota, 4, '0', STR_PAD_RIGHT);
                        }
                    }

                    // --- Auto-fix kode komoditas sesuai flag ---
                    if ($flagKomoditas == '1' && strlen($kodeKomoditas) == 1) {
                        $kodeKomoditas = '0' . $kodeKomoditas;
                    }
                    if ($flagKomoditas == '2' && strlen($kodeKomoditas) == 2) {
                        $kodeKomoditas = '0' . $kodeKomoditas;
                    }
                    if ($flagKomoditas == '3' && strlen($kodeKomoditas) == 6) {
                        $kodeKomoditas = '0' . $kodeKomoditas;
                    }

                    // --- Validasi master wilayah ---
                    if (!master_wilayah::where('kode_wil', $kodeKota)->exists()) {
                        $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": kode wilayah '$kodeKota' tidak ditemukan di master wilayah. Pastikan kode wilayah sudah terdaftar di sistem.";
                        return null;
                    }
                    // --- Validasi master komoditas ---
                    if (!\App\Models\master_komoditas::where('kode_kom', $kodeKomoditas)->exists()) {
                        $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": kode komoditas '$kodeKomoditas' tidak ditemukan di master komoditas. Pastikan kode komoditas sudah terdaftar di sistem.";
                        return null;
                    }

                    // --- Validasi kolom wajib ---
                    foreach ($map as $key => $idx) {
                        if (!isset($row[$idx]) || $row[$idx] === '' || $row[$idx] === null) {
                            $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": kolom $key (" . ($header[$idx] ?? $key) . ") kosong.";
                            return null;
                        }
                    }
                    // --- Validasi angka ---
                    foreach (['inflasi_MtM', 'inflasi_YtD', 'inflasi_YoY', 'andil_MtM', 'andil_YtD', 'andil_YoY'] as $key) {
                        $val = $row[$map[$key]] ?? null;
                        if (!is_null($val) && !is_numeric(trim($val))) {
                            $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": nilai kolom $key (" . ($header[$map[$key]] ?? $key) . ") tidak valid (bukan angka): $val.";
                            return null;
                        }
                    }

                    // --- Return data siap insert ---
                    return [
                        'id_inflasi' => $dataInflasi->id,
                        'id_wil' => $kodeKota,
                        'id_kom' => $kodeKomoditas,
                        'id_flag' => $flagKomoditas,
                        'inflasi_MtM' => $this->processInflasiValue($row[$map['inflasi_MtM']] ?? null),
                        'inflasi_YtD' => $this->processInflasiValue($row[$map['inflasi_YtD']] ?? null),
                        'inflasi_YoY' => $this->processInflasiValue($row[$map['inflasi_YoY']] ?? null),
                        'andil_MtM' => $this->processInflasiValue($row[$map['andil_MtM']] ?? null),
                        'andil_YtD' => $this->processInflasiValue($row[$map['andil_YtD']] ?? null),
                        'andil_YoY' => $this->processInflasiValue($row[$map['andil_YoY']] ?? null),
                        'created_at' => now(),
                    ];
                })
                ->filter()
                ->toArray();
            Log::info('Data to insert:', $dataToInsert);

            // --- VALIDASI DATA GANDA ---
            $duplicateErrors = [];
            $seenCombinations = [];

            foreach ($dataToInsert as $index => $data) {
                $combination = $data['id_wil'] . '-' . $data['id_kom'];
                if (in_array($combination, $seenCombinations)) {
                    // Get the row number (index + 2 because we removed header and arrays are 0-indexed)
                    $rowNumber = $index + 2;
                    $duplicateErrors[] = "Baris ke-{$rowNumber}: kombinasi kode wilayah {$data['id_wil']} dan kode komoditas {$data['id_kom']} sudah ada dalam data yang sama.";
                } else {
                    $seenCombinations[] = $combination;
                }
            }

            if (!empty($duplicateErrors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data berisi kombinasi wilayah dan komoditas yang duplikat.',
                    'errors' => $duplicateErrors,
                ], 422);
            }

            if (!empty($errorRows)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['file' => 'Beberapa baris gagal diimport: ' . implode(', ', $errorRows)]);
            }

            try {
                detail_inflasi::insert($dataToInsert);
            } catch (\Exception $e) {
                Log::error('Insert error: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['file' => 'Gagal menyimpan ke database. Kemungkinan ada data yang tidak valid, duplikat, atau melanggar aturan database.']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupload.',
                'redirect_url' => route('manajemen-data-inflasi.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function edit($id)
    {
        $upload = master_inflasi::with('pengguna')->findOrFail($id);
        return view('prov.manajemen-data-inflasi.edit', compact('upload'));
    }

    public function update(Request $request, $id)
    {
        // Method ini untuk kompatibilitas dengan route yang sudah ada
        // Gunakan updateInflasiAjax untuk fungsionalitas yang lebih lengkap
        return $this->updateInflasiAjax($request, $id);
    }

    public function updateInflasiAjax(Request $request, $id)
    {
        ini_set('memory_limit', '512M');

        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'jenis_data_inflasi' => 'required|in:ASEM 1,ASEM 2,ASEM 3,ATAP',
            'file' => 'nullable|mimes:xlsx'
        ]);

        $upload = master_inflasi::findOrFail($id);

        $rawPeriode = trim($request->periode);
        [$tahun, $bulan] = explode('-', $rawPeriode);

        // validasi periode valid ato tidak
        if (!checkdate($bulan, 1, $tahun)) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tidak valid.',
                'errors' => ['periode' => 'Periode tidak valid.']
            ], 422);
        }

        $periode = Carbon::createFromDate($tahun, $bulan, 1)->toDateString();
        $jenisDataInflasi = $request->jenis_data_inflasi;

        // validasi periode dan jenis tidak bole sama (unique)
        $existingData = master_inflasi::where('periode', $periode)
            ->where('jenis_data_inflasi', $jenisDataInflasi)
            ->where('id', '!=', $id)
            ->exists();

        if ($existingData) {
            return response()->json([
                'success' => false,
                'message' => 'Data untuk periode dan jenis data inflasi terpilih sudah ada. Silakan pilih data lain.',
                'errors' => ['periode' => 'Data untuk periode dan jenis data inflasi terpilih sudah ada.']
            ], 422);
        }

        $carbonPeriode = Carbon::parse($request->periode . '-01', 'Asia/Jakarta')
            ->startOfMonth();

        $nama = 'Data Inflasi ' . $jenisDataInflasi . ' ' .
            $carbonPeriode
            ->locale('id')
            ->translatedFormat('F Y');

        // proses masukin ke master_inflasi
        DB::beginTransaction();
        try {
            // Update master_inflasi
            $upload->update([
                'nama' => $nama,
                'periode' => $periode,
                'jenis_data_inflasi' => $jenisDataInflasi,
            ]);

            // Jika ada file baru, proses file
            if ($request->hasFile('file')) {
                // Hapus detail data lama
                detail_inflasi::where('id_inflasi', $upload->id)->delete();

                $file = $request->file('file');
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(null, true, true, true);

                // Hapus baris kosong di akhir file (trailing empty rows)
                for ($i = count($rows) - 1; $i >= 0; $i--) {
                    if (!array_filter($rows[$i])) {
                        unset($rows[$i]);
                    } else {
                        break;
                    }
                }
                $rows = array_values($rows); // reindex array

                $header = array_shift($rows);

                // Penyesuaian header dan mapping kolom berdasarkan jenis data
                $map = [];
                $indexes = [];
                if (str_starts_with($jenisDataInflasi, 'ASEM')) {
                    $requiredColumns = [
                        'Tahun',
                        'Bulan',
                        'Kd.Kota',
                        'Nama Kota',
                        'Kode',
                        'Nama',
                        'Flag',
                        'IHK',
                        'INF(MOM)',
                        'INF(YTD)',
                        'INF(YOY)',
                        'ANDIL(MOM)',
                        'ANDIL(YTD)',
                        'ANDIL(YOY)'
                    ];
                    $indexes = array_map(fn($col) => array_search($col, $header), $requiredColumns);
                    $map = [
                        'kode_kota' => $indexes[2], // Kd.Kota
                        'kode_komoditas' => $indexes[4], // Kode
                        'flag' => $indexes[6], // Flag
                        'inflasi_MtM' => $indexes[8], // INF(MOM)
                        'inflasi_YtD' => $indexes[9], // INF(YTD)
                        'inflasi_YoY' => $indexes[10], // INF(YOY)
                        'andil_MtM' => $indexes[11], // ANDIL(MOM)
                        'andil_YtD' => $indexes[12], // ANDIL(YTD)
                        'andil_YoY' => $indexes[13], // ANDIL(YOY)
                    ];
                } else {
                    $requiredColumns = [
                        'Kode Kota',
                        'Kode Komoditas',
                        'Flag',
                        'Inflasi MtM',
                        'Inflasi YtD',
                        'Inflasi YoY',
                        'Andil MtM',
                        'Andil YtD',
                        'Andil YoY'
                    ];
                    $indexes = array_map(fn($col) => array_search($col, $header), $requiredColumns);
                    $map = [
                        'kode_kota' => $indexes[0],
                        'kode_komoditas' => $indexes[1],
                        'flag' => $indexes[2],
                        'inflasi_MtM' => $indexes[3],
                        'inflasi_YtD' => $indexes[4],
                        'inflasi_YoY' => $indexes[5],
                        'andil_MtM' => $indexes[6],
                        'andil_YtD' => $indexes[7],
                        'andil_YoY' => $indexes[8],
                    ];
                }

                if (in_array(false, $indexes, true)) {
                    $missingColumns = [];
                    foreach ($requiredColumns as $i => $col) {
                        if ($indexes[$i] === false) {
                            $missingColumns[] = $col;
                        }
                    }
                    // validasi jika ada kolom yang kurang
                    $msg = 'Format file tidak sesuai. Kolom berikut kurang: ' . implode(', ', $missingColumns) . '. Pastikan file memiliki semua kolom yang diperlukan.';
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                        'errors' => ['file' => $msg]
                    ], 422);
                }

                Log::info('Header:', $header);
                Log::info('Indexes:', $indexes);
                Log::info('Rows:', $rows);

                // --- AUTO-FIX DAN VALIDASI KODE KOMODITAS & WILAYAH ---
                $errorRows = [];
                $rowCount = count($rows);
                $dataToInsert = collect($rows)
                    ->map(function ($row, $rowIndex) use ($upload, $map, $rows, $rowCount, $header, $jenisDataInflasi, &$errorRows) {
                        // Skip baris kosong di tengah data
                        if (!array_filter($row)) {
                            if (($rowIndex + 1 < $rowCount) && array_filter($rows[$rowIndex + 1])) {
                                $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": baris kosong.";
                            }
                            return null;
                        }

                        // --- Ambil kode kota dan komoditas ---
                        $kodeKota = trim($row[$map['kode_kota']] ?? '');
                        $kodeKomoditas = $row[$map['kode_komoditas']] ?? '';
                        $flagKomoditas = $row[$map['flag']] ?? '';

                        // --- Auto-fix kode kota ---
                        if (str_starts_with($jenisDataInflasi, 'ASEM')) {
                            if ($kodeKota === '35') {
                                $kodeKota = '3500';
                            }
                        } else {
                            if (is_numeric($kodeKota) && $kodeKota < 1000) {
                                $kodeKota = str_pad($kodeKota, 4, '0', STR_PAD_RIGHT);
                            }
                        }

                        // --- Auto-fix kode komoditas sesuai flag ---
                        if ($flagKomoditas == '1' && strlen($kodeKomoditas) == 1) {
                            $kodeKomoditas = '0' . $kodeKomoditas;
                        }
                        if ($flagKomoditas == '2' && strlen($kodeKomoditas) == 2) {
                            $kodeKomoditas = '0' . $kodeKomoditas;
                        }
                        if ($flagKomoditas == '3' && strlen($kodeKomoditas) == 6) {
                            $kodeKomoditas = '0' . $kodeKomoditas;
                        }

                        // --- Validasi master wilayah ---
                        if (!master_wilayah::where('kode_wil', $kodeKota)->exists()) {
                            $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": kode wilayah '$kodeKota' tidak ditemukan di master wilayah. Pastikan kode wilayah sudah terdaftar di sistem.";
                            return null;
                        }
                        // --- Validasi master komoditas ---
                        if (!\App\Models\master_komoditas::where('kode_kom', $kodeKomoditas)->exists()) {
                            $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": kode komoditas '$kodeKomoditas' tidak ditemukan di master komoditas. Pastikan kode komoditas sudah terdaftar di sistem.";
                            return null;
                        }

                        // --- Validasi cell data ---
                        foreach ($map as $key => $idx) {
                            if (!isset($row[$idx]) || $row[$idx] === '' || $row[$idx] === null) {
                                $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": kolom $key (" . ($header[$idx] ?? $key) . ") kosong.";
                                return null;
                            }
                        }
                        // --- Validasi angka ---
                        foreach (['inflasi_MtM', 'inflasi_YtD', 'inflasi_YoY', 'andil_MtM', 'andil_YtD', 'andil_YoY'] as $key) {
                            $val = $row[$map[$key]] ?? null;
                            if (!is_null($val) && !is_numeric(trim($val))) {
                                $errorRows[] = "Baris ke-" . ($rowIndex + 2) . ": nilai kolom $key (" . ($header[$map[$key]] ?? $key) . ") tidak valid (bukan angka): $val.";
                                return null;
                            }
                        }

                        // --- Return data siap insert ---
                        return [
                            'id_inflasi' => $upload->id,
                            'id_wil' => $kodeKota,
                            'id_kom' => $kodeKomoditas,
                            'id_flag' => $flagKomoditas,
                            'inflasi_MtM' => $this->processInflasiValue($row[$map['inflasi_MtM']] ?? null),
                            'inflasi_YtD' => $this->processInflasiValue($row[$map['inflasi_YtD']] ?? null),
                            'inflasi_YoY' => $this->processInflasiValue($row[$map['inflasi_YoY']] ?? null),
                            'andil_MtM' => $this->processInflasiValue($row[$map['andil_MtM']] ?? null),
                            'andil_YtD' => $this->processInflasiValue($row[$map['andil_YtD']] ?? null),
                            'andil_YoY' => $this->processInflasiValue($row[$map['andil_YoY']] ?? null),
                            'created_at' => now(),
                        ];
                    })
                    ->filter()
                    ->toArray();

                Log::info('Data to insert:', $dataToInsert);

                // --- VALIDASI DATA GANDA ---
                $duplicateErrors = [];
                $seenCombinations = [];

                foreach ($dataToInsert as $index => $data) {
                    $combination = $data['id_wil'] . '-' . $data['id_kom'];
                    if (in_array($combination, $seenCombinations)) {
                        // Get the row number (index + 2 because we removed header and arrays are 0-indexed)
                        $rowNumber = $index + 2;
                        $duplicateErrors[] = "Baris ke-{$rowNumber}: kombinasi kode wilayah {$data['id_wil']} dan kode komoditas {$data['id_kom']} sudah ada dalam data yang sama.";
                    } else {
                        $seenCombinations[] = $combination;
                    }
                }

                if (!empty($duplicateErrors)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data berisi kombinasi wilayah dan komoditas yang duplikat.',
                        'errors' => $duplicateErrors,
                    ], 422);
                }

                if (!empty($errorRows)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Beberapa baris gagal diimport.',
                        'errors' => $errorRows,
                    ], 422);
                }

                try {
                    detail_inflasi::insert($dataToInsert);
                } catch (\Exception $e) {
                    Log::error('Insert error: ' . $e->getMessage());
                    $errorRows[] = 'Gagal menyimpan ke database. Kemungkinan ada data yang tidak valid, duplikat, atau melanggar aturan database.';
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menyimpan ke database.',
                        'errors' => $errorRows,
                    ], 500);
                }
            }

            DB::commit();

            // Jika berhasil, redirect ke index dengan status success
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
                'redirect_url' => route('manajemen-data-inflasi.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update error: ' . $e->getMessage());

            // Jika gagal, kembali ke halaman edit dengan error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data: ' . $e->getMessage(),
                'errors' => ['error' => 'Terjadi kesalahan saat memproses data: ' . $e->getMessage()]
            ], 500);
        }
    }

    public function show(Request $request, $data_name)
    {
        $upload = master_inflasi::where('nama', $data_name)->firstOrFail();
        $search = $request->input('search');
        $user = Auth::user();

        $detailsQuery = detail_inflasi::with(['satker', 'komoditas', 'flag'])
            ->where('id_inflasi', $upload->id);

        // Filter khusus untuk admin kabkot (id_role == 2)
        if ($user->id_role == 2) {
            $detailsQuery->where('id_wil', $user->id_satker);
        }

        $details = $detailsQuery
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhereHas('satker', function ($sq) use ($search) {
                        $sq->where('nama_satker', 'like', "%{$search}%");
                    })
                        ->orWhereHas('komoditas', function ($sq) use ($search) {
                            $sq->where('nama_kom', 'like', "%{$search}%");
                        })
                        ->orWhereHas('flag', function ($sq) use ($search) {
                            $sq->where('flag', 'like', "%{$search}%");
                        })
                        ->orWhere('id_wil', 'like', "%{$search}%")
                        ->orWhere('id_kom', 'like', "%{$search}%")
                        ->orWhere('id_flag', 'like', "%{$search}%");
                });
            })
            ->orderBy('id_wil', 'asc')
            ->paginate(10);

        return view('prov.manajemen-data-inflasi.show', compact('upload', 'details'));
    }

    public function destroy($id)
    {
        $upload = master_inflasi::findOrFail($id);
        detail_inflasi::where('id_inflasi', $upload->id)->delete();
        $upload->delete();
        return redirect()->route('manajemen-data-inflasi.index')->with('success', 'Data berhasil dihapus beserta detail terkait.');
    }
}
