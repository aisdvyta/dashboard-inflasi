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
    public function index()
    {
        $uploads = master_inflasi::with('pengguna')->orderBy('upload_at', 'desc')->get();
        return view('prov.manajemen-data-inflasi.index', compact('uploads'));
    }

    public function create()
    {
        return view('prov.manajemen-data-inflasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'jenis_data_inflasi' => 'required',
            'file' => 'required|mimes:xlsx'
        ]);

        // Generate nama otomatis
        $periode = Carbon::createFromFormat('Y-m', $request->periode);
        $nama = 'data inflasi ' . $request->jenis_data_inflasi . ' ' . $periode->translatedFormat('F Y');

        // Simpan ke master_inflasi terlebih dahulu
        $dataInflasi = master_inflasi::create([
            'id_pengguna' => Auth::user()->id, // Ambil ID pengguna yang sedang login
            'nama' => $nama,
            'periode' => $periode->startOfMonth()->toDateString(),
            'jenis_data_inflasi' => $request->jenis_data_inflasi,
            'upload_at' => now(),
        ]);

        // Ambil ID master_inflasi yang baru saja dibuat
        $idInflasi = $dataInflasi->id;

        // Proses file Excel
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        // Ambil header untuk mapping indeks kolom
        $header = array_shift($rows);

        // Mapping indeks berdasarkan header
        $indexKodeKota = array_search('Kode Kota', $header);
        $indexKodeKomoditas = array_search('Kode Komoditas', $header);
        $indexFlag = array_search('Flag', $header);
        $indexInflasiMtM = array_search('Inflasi MtM', $header);
        $indexInflasiYtD = array_search('Inflasi YtD', $header);
        $indexInflasiYoY = array_search('Inflasi YoY', $header);
        $indexAndilMtM = array_search('Andil MtM', $header);
        $indexAndilYtD = array_search('Andil YtD', $header);
        $indexAndilYoY = array_search('Andil YoY', $header);

        // Loop setiap baris data di Excel dan simpan ke tabel detail_inflasi
        foreach ($rows as $row) {
            detail_inflasi::create([
                'id_inflasi' => $idInflasi,
                'id_wil' => $row[$indexKodeKota] ?? null,
                'id_kom' => sprintf('%s', $row[$indexKodeKomoditas] ?? ''),
                'id_flag' => $row[$indexFlag] ?? null,
                'inflasi_MtM' => isset($row[$indexInflasiMtM]) ? round(floatval($row[$indexInflasiMtM]), 2) : null,
                'inflasi_YtD' => isset($row[$indexInflasiYtD]) ? round(floatval($row[$indexInflasiYtD]), 2) : null,
                'inflasi_YoY' => isset($row[$indexInflasiYoY]) ? round(floatval($row[$indexInflasiYoY]), 2) : null,
                'andil_MtM' => isset($row[$indexAndilMtM]) ? round(floatval($row[$indexAndilMtM]), 2) : null,
                'andil_YtD' => isset($row[$indexAndilYtD]) ? round(floatval($row[$indexAndilYtD]), 2) : null,
                'andil_YoY' => isset($row[$indexAndilYoY]) ? round(floatval($row[$indexAndilYoY]), 2) : null,
                'created_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'File berhasil diupload dan data berhasil disimpan!');
    }

    public function uploadInflasiAjax(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:Y-m',
            'jenis_data_inflasi' => 'required',
            'file' => 'required|mimes:xlsx'
        ]);

        $periode = Carbon::createFromFormat('Y-m', $request->periode);
        $nama = 'data inflasi ' . $request->jenis_data_inflasi . ' ' . $periode->translatedFormat('F Y');

        $dataInflasi = master_inflasi::create([
            'id_pengguna' => Auth::user()->id,
            'nama' => $nama,
            'periode' => $periode->startOfMonth()->toDateString(),
            'jenis_data_inflasi' => $request->jenis_data_inflasi,
            'upload_at' => now(),
        ]);

        $idInflasi = $dataInflasi->id;

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);

            $header = array_shift($rows);

            // Mapping kolom
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

            foreach ($rows as $row) {
                detail_inflasi::create([
                    'id_inflasi' => $idInflasi,
                    'id_wil' => $row[$indexKodeKota] ?? null,
                    'id_kom' => sprintf('%s', $row[$indexKodeKomoditas] ?? ''),
                    'id_flag' => $row[$indexFlag] ?? null,
                    'inflasi_MtM' => isset($row[$indexInflasiMtM]) ? round(floatval($row[$indexInflasiMtM]), 2) : null,
                    'inflasi_YtD' => isset($row[$indexInflasiYtD]) ? round(floatval($row[$indexInflasiYtD]), 2) : null,
                    'inflasi_YoY' => isset($row[$indexInflasiYoY]) ? round(floatval($row[$indexInflasiYoY]), 2) : null,
                    'andil_MtM' => isset($row[$indexAndilMtM]) ? round(floatval($row[$indexAndilMtM]), 2) : null,
                    'andil_YtD' => isset($row[$indexAndilYtD]) ? round(floatval($row[$indexAndilYtD]), 2) : null,
                    'andil_YoY' => isset($row[$indexAndilYoY]) ? round(floatval($row[$indexAndilYoY]), 2) : null,
                    'created_at' => now(),
                ]);

                $insertedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengupload!',
                'inserted_rows' => $insertedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat proses data: ' . $e->getMessage()
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

        // Jika ada file baru, proses file tersebut
        if ($request->hasFile('file')) {
            // Hapus data lama di detail_inflasi
            detail_inflasi::where('id_inflasi', $upload->id)->delete();

            // Proses file baru
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);

            // Ambil header untuk mapping indeks kolom
            $header = array_shift($rows);

            // Mapping indeks berdasarkan header
            $indexKodeKota = array_search('Kode Kota', $header);
            $indexKodeKomoditas = array_search('Kode Komoditas', $header);
            $indexFlag = array_search('Flag', $header);
            $indexInflasiMtM = array_search('Inflasi MtM', $header);
            $indexInflasiYtD = array_search('Inflasi YtD', $header);
            $indexInflasiYoY = array_search('Inflasi YoY', $header);
            $indexAndilMtM = array_search('Andil MtM', $header);
            $indexAndilYtD = array_search('Andil YtD', $header);
            $indexAndilYoY = array_search('Andil YoY', $header);

            // Loop setiap baris data di Excel dan simpan ke tabel detail_inflasi
            foreach ($rows as $row) {
                detail_inflasi::create([
                    'id_inflasi' => $upload->id,
                    'id_wil' => $row[$indexKodeKota] ?? null,
                    'id_kom' => sprintf('%s', $row[$indexKodeKomoditas] ?? ''),
                    'id_flag' => $row[$indexFlag] ?? null,
                    'inflasi_MtM' => isset($row[$indexInflasiMtM]) ? round(floatval($row[$indexInflasiMtM]), 2) : null,
                    'inflasi_YtD' => isset($row[$indexInflasiYtD]) ? round(floatval($row[$indexInflasiYtD]), 2) : null,
                    'inflasi_YoY' => isset($row[$indexInflasiYoY]) ? round(floatval($row[$indexInflasiYoY]), 2) : null,
                    'andil_MtM' => isset($row[$indexAndilMtM]) ? round(floatval($row[$indexAndilMtM]), 2) : null,
                    'andil_YtD' => isset($row[$indexAndilYtD]) ? round(floatval($row[$indexAndilYtD]), 2) : null,
                    'andil_YoY' => isset($row[$indexAndilYoY]) ? round(floatval($row[$indexAndilYoY]), 2) : null,
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
            ->get();

        return view('prov.manajemen-data-inflasi.show', compact('upload', 'details'));
    }

    public function destroy($id)
    {
        // Cari data di tabel master_inflasi berdasarkan ID
        $upload = master_inflasi::findOrFail($id);

        // Hapus semua data terkait di tabel detail_inflasi
        detail_inflasi::where('id_inflasi', $upload->id)->delete();

        // Hapus data di tabel master_inflasi
        $upload->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('manajemen-data-inflasi.index')->with('success', 'Data berhasil dihapus beserta detail terkait.');
    }
}
