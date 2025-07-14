<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_inflasi;
use App\Models\detail_inflasi;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class DaftarTabelInflasiController extends Controller
{
    public function index(Request $request)
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

        return view('user.daftar-tabel-inflasi.index', compact('uploads', 'search'));
    }

    public function show(Request $request, $id)
    {
        $upload = master_inflasi::findOrFail($id);

        // Pastikan hanya data ATAP yang bisa diakses
        if ($upload->jenis_data_inflasi !== 'ATAP') {
            abort(404);
        }

        $search = $request->input('search');

        // Ambil data detail_inflasi untuk Provinsi Jawa Timur (kode 3500) dengan flag 0 dan 1
        $details = detail_inflasi::with(['komoditas'])
            ->join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_inflasi', $upload->id)
            ->where('detail_inflasis.id_wil', '3500') // Provinsi Jawa Timur
            ->whereIn('detail_inflasis.id_flag', [0, 1]) // Hanya flag 0 dan 1
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhere('mk.nama_kom', 'like', "%{$search}%")
                        ->orWhere('mk.kode_kom', 'like', "%{$search}%")
                        ->orWhere('detail_inflasis.id_flag', 'like', "%{$search}%");
                });
            })
            ->orderBy('mk.kode_kom', 'asc')
            ->select(
                'mk.kode_kom',
                'mk.nama_kom',
                'detail_inflasis.id_flag',
                'detail_inflasis.inflasi_MtM',
                'detail_inflasis.inflasi_YtD',
                'detail_inflasis.inflasi_YoY',
                'detail_inflasis.andil_MtM',
                'detail_inflasis.andil_YtD',
                'detail_inflasis.andil_YoY'
            )
            ->paginate(15);

        $periode = Carbon::parse($upload->periode);
        $displayName = 'Data Inflasi Provinsi Jawa Timur Menurut Kelompok Pengeluaran Bulan ' .
            $periode->locale('id')->translatedFormat('F Y');

        return view('user.daftar-tabel-inflasi.show', compact('upload', 'details', 'displayName', 'search'));
    }

    public function download($id)
    {
        $upload = master_inflasi::findOrFail($id);

        // Pastikan hanya data ATAP yang bisa diakses
        if ($upload->jenis_data_inflasi !== 'ATAP') {
            abort(404);
        }

        // Ambil data untuk Provinsi Jawa Timur (kode 3500) dengan flag 0 dan 1
        $details = detail_inflasi::with(['komoditas'])
            ->join('master_inflasis', 'detail_inflasis.id_inflasi', '=', 'master_inflasis.id')
            ->join('master_komoditas as mk', 'detail_inflasis.id_kom', '=', 'mk.kode_kom')
            ->where('detail_inflasis.id_inflasi', $upload->id)
            ->where('detail_inflasis.id_wil', '3500') // Provinsi Jawa Timur
            ->whereIn('detail_inflasis.id_flag', [0, 1]) // Hanya flag 0 dan 1
            ->orderBy('mk.kode_kom', 'asc')
            ->select(
                'mk.kode_kom',
                'mk.nama_kom',
                'detail_inflasis.id_flag',
                'detail_inflasis.inflasi_MtM',
                'detail_inflasis.inflasi_YtD',
                'detail_inflasis.inflasi_YoY',
                'detail_inflasis.andil_MtM',
                'detail_inflasis.andil_YtD',
                'detail_inflasis.andil_YoY'
            )
            ->get();

        // Buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul
        $periode = Carbon::parse($upload->periode);
        $judul = 'Data Inflasi Provinsi Jawa Timur Menurut Kelompok Pengeluaran Bulan ' .
            $periode->locale('id')->translatedFormat('F Y');

        $sheet->setCellValue('A1', $judul);
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Header tabel
        $headers = [
            'A3' => 'No.',
            'B3' => 'Nama Kelompok Pengeluaran',
            'C3' => 'Inflasi MtM (%)',
            'D3' => 'Inflasi YtD (%)',
            'E3' => 'Inflasi YoY (%)',
            'F3' => 'Andil MtM (%)',
            'G3' => 'Andil YtD (%)',
            'H3' => 'Andil YoY (%)'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal('center');
        }

        // Isi data
        $row = 4;
        $no = 1;
        foreach ($details as $detail) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $detail->nama_kom);
            $sheet->setCellValue('C' . $row, $detail->inflasi_MtM);
            $sheet->setCellValue('D' . $row, $detail->inflasi_YtD);
            $sheet->setCellValue('E' . $row, $detail->inflasi_YoY);
            $sheet->setCellValue('F' . $row, $detail->andil_MtM);
            $sheet->setCellValue('G' . $row, $detail->andil_YtD);
            $sheet->setCellValue('H' . $row, $detail->andil_YoY);
            $sheet->getStyle('C' . $row . ':H' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $row++;
            $no++;
        }

        // Auto size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border untuk seluruh tabel
        $lastRow = $row - 1;
        $sheet->getStyle('A3:H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Buat writer dan download
        $writer = new Xlsx($spreadsheet);
        $filename = "Data_Inflasi_Jatim_" . $periode->format('F_Y') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
