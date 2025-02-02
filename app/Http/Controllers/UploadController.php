<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data_inflasi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadController extends Controller
{
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
}
