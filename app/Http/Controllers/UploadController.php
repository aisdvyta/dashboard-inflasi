<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\master_inflasi;
use App\Models\detail_inflasi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    //FUNGSI TAMPILIN, SEARCH, N PAGINATION
    public function landing(Request $request)
    {
        $search = $request->input('search');

        $uploads = master_inflasi::with('pengguna')
            ->where('jenis_data_inflasi', 'ATAP') // Tambahkan kondisi untuk hanya menampilkan "ATAP"
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('jenis_data_inflasi', 'like', "%{$search}%");
                });
            })
            ->orderBy('periode', 'desc')
            ->paginate(10);

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
            ? ceil($number * 100) / 100 // Untuk angka negatif
            : floor($number * 100) / 100; // Untuk angka positif
    }

    private function processInflasiValue($value)
    {
        // Periksa apakah nilai ada dan merupakan angka
        if (isset($value) && is_numeric(trim($value))) {
            return $this->truncateToTwoDecimals(floatval(trim($value)));
        }
        return null;
    }

    public function uploadInflasiAjax(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'jenis_data_inflasi' => 'required|in:ASEM 1,ASEM 2,ASEM 3,ATAP',
            'file' => 'required|mimes:xlsx'
        ]);

        $periode = Carbon::createFromFormat('Y-m', $request->periode, 'UTC')
            ->startOfMonth()
            ->setTimezone(config('app.timezone'))
            ->toDateString();

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

        $nama = 'Data Inflasi ' . $jenisDataInflasi . ' ' . Carbon::createFromFormat('Y-m', $request->periode, 'UTC')
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

            $header = array_shift($rows);

            $requiredColumns = ['Kode Kota', 'Kode Komoditas', 'Flag', 'Inflasi MtM', 'Inflasi YtD', 'Inflasi YoY', 'Andil MtM', 'Andil YtD', 'Andil YoY'];
            $indexes = array_map(fn($col) => array_search($col, $header), $requiredColumns);

            if (in_array(false, $indexes, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format file tidak sesuai. Pastikan file memiliki kolom yang diperlukan.',
                ], 422);
            }

            $dataToInsert = collect($rows)
                ->filter(fn($row) => array_filter($row)) // <- Ini yang buang baris kosong
                ->map(function ($row) use ($dataInflasi, $indexes) {
                    return [
                        'id_inflasi' => $dataInflasi->id,
                        'id_wil' => $row[$indexes[0]] ?? null,
                        'id_kom' => $row[$indexes[1]] ?? '',
                        'id_flag' => $row[$indexes[2]] ?? null,
                        'inflasi_MtM' => $this->processInflasiValue($row[$indexes[3]] ?? null),
                        'inflasi_YtD' => $this->processInflasiValue($row[$indexes[4]] ?? null),
                        'inflasi_YoY' => $this->processInflasiValue($row[$indexes[5]] ?? null),
                        'andil_MtM' => $this->processInflasiValue($row[$indexes[6]] ?? null),
                        'andil_YtD' => $this->processInflasiValue($row[$indexes[7]] ?? null),
                        'andil_YoY' => $this->processInflasiValue($row[$indexes[8]] ?? null),
                        'created_at' => now(),
                    ];
                })->toArray();

            detail_inflasi::insert($dataToInsert);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupload.',
                'redirect_url' => route('manajemen-data-inflasi.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'jenis_data_inflasi' => 'required',
            'file' => 'nullable|mimes:xlsx'
        ]);

        $upload = master_inflasi::findOrFail($id);

        // Update data master_inflasi
        $upload->update([
            'periode' => Carbon::createFromFormat('Y-m', $request->periode)->startOfMonth()->toDateString(),
            'jenis_data_inflasi' => $request->jenis_data_inflasi,
        ]);

        if ($request->hasFile('file')) {
            // Hapus data lama di detail_inflasi
            detail_inflasi::where('id_inflasi', $upload->id)->delete();

            // Proses file baru
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);

            $header = array_shift($rows);

            $indexKodeKota = array_search('Kode Kota', $header);
            $indexKodeKomoditas = array_search('Kode Komoditas', $header);
            $indexFlag = array_search('Flag', $header);
            $indexInflasiMtM = array_search('Inflasi MtM', $header);
            $indexInflasiYtD = array_search('Inflasi YtD', $header);
            $indexInflasiYoY = array_search('Inflasi YoY', $header);
            $indexAndilMtM = array_search('Andil MtM', $header);
            $indexAndilYtD = array_search('Andil YtD', $header);
            $indexAndilYoY = array_search('Andil YoY', $header);

            foreach ($rows as $row) {
                detail_inflasi::create([
                    'id_inflasi' => $upload->id,
                    'id_wil' => $row[$indexKodeKota] ?? null,
                    'id_kom' => sprintf('%s', $row[$indexKodeKomoditas] ?? ''), //rawan
                    'id_flag' => $row[$indexFlag] ?? null,
                    'inflasi_MtM' => isset($row[$indexInflasiMtM]) && is_numeric(trim($row[$indexInflasiMtM])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexInflasiMtM]))) : null,
                    'inflasi_YtD' => isset($row[$indexInflasiYtD]) && is_numeric(trim($row[$indexInflasiYtD])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexInflasiYtD]))) : null,
                    'inflasi_YoY' => isset($row[$indexInflasiYoY]) && is_numeric(trim($row[$indexInflasiYoY])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexInflasiYoY]))) : null,
                    'andil_MtM' => isset($row[$indexAndilMtM]) && is_numeric(trim($row[$indexAndilMtM])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexAndilMtM]))) : null,
                    'andil_YtD' => isset($row[$indexAndilYtD]) && is_numeric(trim($row[$indexAndilYtD])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexAndilYtD]))) : null,
                    'andil_YoY' => isset($row[$indexAndilYoY]) && is_numeric(trim($row[$indexAndilYoY])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexAndilYoY]))) : null,
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->route('manajemen-data-inflasi.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function show($data_name)
    {
        // Cari data berdasarkan nama file
        $upload = master_inflasi::where('nama', $data_name)->firstOrFail();

        // Ambil data detail_inflasi yang terkait dengan master_inflasi
        $details = detail_inflasi::with(['satker', 'komoditas'])
            ->where('id_inflasi', $upload->id)
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
