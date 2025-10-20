<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPerpetualExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $reportData;
    protected $tahun;

    public function __construct($reportData, $tahun)
    {
        $this->reportData = $reportData;
        $this->tahun = $tahun;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('laporan.perpetual.export', [
            'reportData' => $this->reportData,
            'tahun' => $this->tahun,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Membuat header menjadi bold
        $sheet->getStyle('A1:CL3')->getFont()->setBold(true);
        // Tengahkan header
        $sheet->getStyle('A1:CL3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }
}