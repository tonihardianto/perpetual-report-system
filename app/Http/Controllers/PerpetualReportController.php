<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Exports\LaporanPerpetualExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ReportService;
use Illuminate\Http\Request;

class PerpetualReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $obatIds = $request->input('obat_id', []);
        
        // Get selected obats for displaying in select2
        $selectedObats = [];
        if (!empty($obatIds)) {
            $selectedObats = Obat::whereIn('id', $obatIds)->get();
        }
        
        $obats = Obat::orderBy('nama_obat')->get();
        $reportData = [];

        // Generate report jika ada parameter tahun atau obat_id
        if ($request->has('tahun') || $request->has('obat_id')) {
            $reportData = $this->reportService->generatePerpetualReport((int)$tahun, $obatIds);
            \Log::info('Report Data:', ['count' => count($reportData), 'sample' => !empty($reportData) ? $reportData[0] : null]);
        }

        $years = range(date('Y'), 2020); // Tahun laporan dari 2020 hingga tahun ini
        
        return view('laporan.perpetual.index', compact('reportData', 'obats', 'tahun', 'years', 'obatIds'));
    }

    public function export(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $obatIds = $request->input('obat_id', []);

        // Ambil data yang sama dengan yang ditampilkan di halaman
        $reportData = $this->reportService->generatePerpetualReport((int)$tahun, $obatIds);

        $fileName = 'Laporan_Perpetual_' . $tahun . '.xlsx';
        return Excel::download(new LaporanPerpetualExport($reportData, $tahun), $fileName);
    }
}