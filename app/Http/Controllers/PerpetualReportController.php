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
        $obatId = $request->input('obat_id') ? (int)$request->input('obat_id') : null;
        
        $obats = Obat::orderBy('nama_obat')->get();
        $reportData = collect();

        if ($request->has('view_report')) {
            // Kode yang Benar
            $reportData = $reportService->generatePerpetualReport($year);
        }

        $years = range(date('Y'), 2020); // Tahun laporan dari 2020 hingga tahun ini
        
        return view('laporan.perpetual.index', compact('reportData', 'obats', 'tahun', 'years', 'obatId'));
    }

    public function export(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $obatId = $request->input('obat_id') ? (int)$request->input('obat_id') : null;

        // Ambil data yang sama dengan yang ditampilkan di halaman
        $reportData = $this->reportService->generatePerpetualReport((int)$tahun, $obatId);

        $fileName = 'Laporan_Perpetual_' . $tahun . '.xlsx';
        return Excel::download(new LaporanPerpetualExport($reportData, $tahun), $fileName);
    }
}