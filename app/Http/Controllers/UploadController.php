<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\master_inflasi;
use App\Models\detail_inflasi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class UploadController extends Controller
{
    //FUNGSI TAMPILIN, SEARCH, N PAGINATION
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

    public function uploadInflasiAjax(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'jenis_data_inflasi' => 'required',
            'file' => 'required|mimes:xlsx'
        ]);

        $periode = Carbon::createFromFormat('Y-m', $request->periode)->startOfMonth()->toDateString();
        $jenisDataInflasi = $request->jenis_data_inflasi;

        $existingData = master_inflasi::where('periode', $periode)
            ->where('jenis_data_inflasi', $jenisDataInflasi)
            ->first();

        if ($existingData) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'periode' => 'Data untuk periode dan jenis data inflasi terpilih sudah ada. Silakan pilih data lain.'
                ]
            ], 422);
        }

        $nama = 'Data Inflasi ' . $jenisDataInflasi . ' ' . Carbon::createFromFormat('Y-m', $request->periode)->translatedFormat('F Y');

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

        $indexKodeKota = array_search('Kode Kota', $header);
        $indexKodeKomoditas = array_search('Kode Komoditas', $header);
        $indexFlag = array_search('Flag', $header);
        $indexInflasiMtM = array_search('Inflasi MtM', $header);
        $indexInflasiYtD = array_search('Inflasi YtD', $header);
        $indexInflasiYoY = array_search('Inflasi YoY', $header);
        $indexAndilMtM = array_search('Andil MtM', $header);
        $indexAndilYtD = array_search('Andil YtD', $header);
        $indexAndilYoY = array_search('Andil YoY', $header);

        $insertedCount = 0;
        $errorCount = 0;

        foreach ($rows as $row) {
            try {
                detail_inflasi::create([
                    'id_inflasi' => $dataInflasi->id,
                    'id_wil' => $row[$indexKodeKota] ?? null,
                    'id_kom' => sprintf('%s', $row[$indexKodeKomoditas] ?? ''),
                    'id_flag' => $row[$indexFlag] ?? null,
                    'inflasi_MtM' => isset($row[$indexInflasiMtM]) && is_numeric(trim($row[$indexInflasiMtM])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexInflasiMtM]))) : null,
                    'inflasi_YtD' => isset($row[$indexInflasiYtD]) && is_numeric(trim($row[$indexInflasiYtD])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexInflasiYtD]))) : null,
                    'inflasi_YoY' => isset($row[$indexInflasiYoY]) && is_numeric(trim($row[$indexInflasiYoY])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexInflasiYoY]))) : null,
                    'andil_MtM' => isset($row[$indexAndilMtM]) && is_numeric(trim($row[$indexAndilMtM])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexAndilMtM]))) : null,
                    'andil_YtD' => isset($row[$indexAndilYtD]) && is_numeric(trim($row[$indexAndilYtD])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexAndilYtD]))) : null,
                    'andil_YoY' => isset($row[$indexAndilYoY]) && is_numeric(trim($row[$indexAndilYoY])) ? $this->truncateToTwoDecimals(floatval(trim($row[$indexAndilYoY]))) : null,
                    'created_at' => now(),
                ]);

                $insertedCount++;
            } catch (\Exception $e) {
                $errorCount++;
                \Log::error('Error inserting row: ' . $e->getMessage(), ['row' => $row]);
            }
        }

        if ($insertedCount > 0) {
            return response()->json([
                'success' => true,
                'message' => $errorCount > 0
                    ? "Data berhasil diupload dengan beberapa error. Jumlah error: $errorCount"
                    : "Semua data berhasil diupload.",
                'redirect_url' => route('manajemen-data-inflasi.index'),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang berhasil diupload. Periksa format file Anda.',
            ], 422);
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
