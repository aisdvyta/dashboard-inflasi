<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data_inflasi;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $latestPeriod = data_inflasi::latest('created_at')->value('period');
        return $this->filterData($latestPeriod);
    }

    public function filter(Request $request)
    {
        $selectedPeriod = $request->input('period', data_inflasi::latest('created_at')->value('period'));
        return $this->filterData($selectedPeriod);
    }

    private function filterData($period)
    {
        $dataInflasi = data_inflasi::where('period', $period)->first();

        if (!$dataInflasi) {
            return view('dashboard.index', [
                'periods' => data_inflasi::distinct()->pluck('period'),
                'selectedPeriod' => $period,
                'chartData' => null,
                'chartDataYTD' => null,
                'chartDataYoY' => null
            ]);
        }

        // Baca CSV
        $csvPath = storage_path('app/' . str_replace('/storage', 'public', $dataInflasi->file_path));
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);
        $records = iterator_to_array($csv->getRecords());

        // Filter Flag = 3
        $filteredData = array_filter($records, fn($row) => $row['Flag'] == 3);

        // Ambil Top 10 Inflasi MtM
        usort($filteredData, fn($a, $b) => $b['Inflasi MtM'] <=> $a['Inflasi MtM']);
        $top10MtM = array_slice($filteredData, 0, 10);

        // Ambil Top 10 Inflasi YTD
        usort($filteredData, fn($a, $b) => $b['Inflasi YtD'] <=> $a['Inflasi YtD']);
        $top10YtD = array_slice($filteredData, 0, 10);

        // Ambil Top 10 Inflasi YoY
        usort($filteredData, fn($a, $b) => $b['Inflasi YoY'] <=> $a['Inflasi YoY']);
        $top10YoY = array_slice($filteredData, 0, 10);

        return view('dashboard.index', [
            'periods' => data_inflasi::distinct()->pluck('period'),
            'selectedPeriod' => $period,
            'chartData' => $top10MtM,
            'chartDataYtD' => $top10YtD,
            'chartDataYoY' => $top10YoY
        ]);
    }
}
