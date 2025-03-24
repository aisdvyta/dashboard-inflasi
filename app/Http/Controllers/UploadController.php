<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_inflasi;
use App\Models\detail_inflasi;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = master_inflasi::orderBy('upload_at', 'desc')->get();
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
            'jenis_master_inflasi' => 'required',
            'file' => 'required|mimes:xlsx'
        ]);

        // Generate nama otomatis
        $periode = Carbon::createFromFormat('Y-m', $request->periode);
        $nama = 'data inflasi ' . $request->jenis_master_inflasi . ' ' . $periode->translatedFormat('F Y');

        // Simpan ke master_inflasi terlebih dahulu
        $dataInflasi = master_inflasi::create([
            'id_pengguna' => '01', // Tambahkan ini
            'nama' => $nama,
            'periode' => $periode->startOfMonth()->toDateString(), // Ubah ke format YYYY-MM-01
            'jenis_master_inflasi' => $request->jenis_master_inflasi,
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
                'id_kom' => $row[$indexKodeKomoditas] ?? null,
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


    public function show($data_name)
    {
        // Cari data berdasarkan nama file
        $upload = master_inflasi::where('nama', $data_name)->first();

        // Jika tidak ditemukan, kembalikan error 404
        if (!$upload) {
            return abort(404, 'Data tidak ditemukan');
        }

        // Ambil data dari tabel detail_inflasi
        $detail_inflasiData = detail_inflasi::where('id_inflasi', $upload->id)->get();

        return view('import.show', compact('upload', 'detail_inflasiData'));
    }
}
