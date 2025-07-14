<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\detail_inflasi;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ChartController extends Controller
{
    public function index()
    {
        $uploads = detail_inflasi::orderBy('created_at', 'desc')->get();
        $chartsData = [];

        foreach ($uploads as $upload) {
            $csvPath = storage_path('app/' . str_replace('/storage', 'public', $upload->file_path));

            if (Storage::exists(str_replace('/storage', 'public', $upload->file_path))) {
                $csv = Reader::createFromPath($csvPath, 'r');
                $csv->setHeaderOffset(0);

                $data = [];
                foreach ($csv as $record) {
                    if (isset($record['Flag']) && $record['Flag'] == '0') {
                        $data[] = [
                            'nama_kota' => $record['Nama Kota'] ?? 'Unknown',
                            'ihk' => $record['IHK'] ?? 0
                        ];
                    }
                }

                $chartsData[] = [
                    'data_name' => $upload->data_name,
                    'data' => $data
                ];
            }
        }

        return view('dashboard.index', compact('chartsData'));
    }
}

