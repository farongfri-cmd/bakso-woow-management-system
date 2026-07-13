<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
    }

    public function index(Request $request)
    {
        return view('laporan.index', $this->reportService->getIndexData($request));
    }

    public function excel(Request $request)
    {
        $data = $this->reportService->getExcelData($request);
        $filename = 'laporan-bakso-woow-' . now()->format('Ymd-His') . '.xls';

        return response()
            ->view('laporan.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function pdf()
    {
        $pdf = Pdf::loadView('laporan.pdf', $this->reportService->getPdfData());

        return $pdf->download('laporan-penjualan.pdf');
    }
}
