<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data_inflasi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = data_inflasi::orderBy('created_at', 'desc')->get();
        return view('prov.manajemen-data-inflasi.index', compact('uploads'));
    }

    public function create()
    {
        return view('prov.manajemen-data-inflasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'period' => 'required',
            'category' => 'required',
            'file' => 'required|mimes:xlsx'
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $csvFileName = time() . '.csv';
        $csvFilePath = 'public/csv/' . $csvFileName;

        // Konversi XLSX ke CSV
        $writer = IOFactory::createWriter($spreadsheet, 'Csv');
        Storage::put($csvFilePath, '');
        $writer->save(storage_path('app/' . $csvFilePath));

        // Simpan data ke database
        $upload = data_inflasi::create([
            'username' => $request->username,
            'data_name' => 'Data ' . $request->category . ' ' . $request->period,
            'period' => $request->period,
            'category' => $request->category,
            'file_path' => Storage::url($csvFilePath),
        ]);

        return redirect()->back()->with('success', 'File berhasil diupload dan dikonversi!');
    }

    public function show($data_name)
    {
        // Cari data berdasarkan nama file
        $upload = data_inflasi::where('data_name', $data_name)->first();

        // Jika tidak ditemukan, kembalikan error 404
        if (!$upload) {
            return abort(404, 'Data tidak ditemukan');
        }

        // Baca isi file CSV
        $filePath = storage_path('app/public/csv/' . basename($upload->file_path));
        if (!file_exists($filePath)) {
            return abort(404, 'File tidak ditemukan');
        }

        // Ambil isi file CSV
        $csvData = array_map('str_getcsv', file($filePath));

        return view('import.show', compact('upload', 'csvData'));
    }
}
