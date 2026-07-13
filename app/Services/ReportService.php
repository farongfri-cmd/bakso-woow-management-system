<?php

namespace App\Services;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get report index data.
     */
    public function getIndexData(Request $request): array
    {
        $query = $this->laporanPenjualanQuery($request);
        $summary = $this->laporanSummary($request);
        $topMenu = $this->topMenuTerlaris($request);

        $total_semua = $summary->total_pendapatan ?? 0;
        $this->applySearch($query, $request);

        $laporan = $query->paginate(10)->withQueryString();
        $stokBahanBaku = BahanBaku::orderBy('nama_bahan')->get();

        return compact('laporan', 'total_semua', 'stokBahanBaku', 'summary', 'topMenu');
    }

    /**
     * Get Excel report data.
     */
    public function getExcelData(Request $request): array
    {
        $query = $this->laporanPenjualanQuery($request);
        $laporan = $query->get();
        $total_semua = (clone $query)->sum('detail_penjualan.subtotal');
        $stokBahanBaku = BahanBaku::orderBy('nama_bahan')->get();

        return compact('laporan', 'total_semua', 'stokBahanBaku');
    }

    /**
     * Get PDF report data.
     */
    public function getPdfData(): array
    {
        $laporan = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('menu', 'detail_penjualan.id_menu', '=', 'menu.id_menu')
            ->select(
                'penjualan.tanggal',
                'menu.nama_menu',
                'detail_penjualan.jumlah',
                'detail_penjualan.subtotal'
            )
            ->get();

        $total_semua = DB::table('detail_penjualan')->sum('subtotal');

        return compact('laporan', 'total_semua');
    }

    private function laporanPenjualanQuery(Request $request)
    {
        $query = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('menu', 'detail_penjualan.id_menu', '=', 'menu.id_menu')
            ->select(
                'penjualan.tanggal',
                'menu.nama_menu',
                'detail_penjualan.jumlah',
                'detail_penjualan.subtotal'
            );

        if ($request->dari && $request->sampai) {
            $query->whereBetween('penjualan.tanggal', [
                $request->dari,
                $request->sampai,
            ]);
        }

        return $query;
    }

    private function applySearch($query, Request $request): void
    {
        $search = trim((string) $request->search);

        if ($search !== '') {
            $query->where('menu.nama_menu', 'like', '%' . $search . '%');
        }
    }

    private function laporanSummary(Request $request)
    {
        $query = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->selectRaw('
                COALESCE(SUM(detail_penjualan.subtotal), 0) as total_pendapatan,
                COUNT(DISTINCT penjualan.id_penjualan) as total_transaksi,
                COALESCE(SUM(detail_penjualan.jumlah), 0) as produk_terjual
            ');

        if ($request->dari && $request->sampai) {
            $query->whereBetween('penjualan.tanggal', [
                $request->dari,
                $request->sampai,
            ]);
        }

        return $query->first();
    }

    private function topMenuTerlaris(Request $request)
    {
        $query = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('menu', 'detail_penjualan.id_menu', '=', 'menu.id_menu')
            ->select(
                'menu.id_menu',
                'menu.nama_menu',
                DB::raw('COALESCE(SUM(detail_penjualan.jumlah), 0) as total_terjual'),
                DB::raw('COALESCE(SUM(detail_penjualan.subtotal), 0) as total_pendapatan')
            )
            ->groupBy('menu.id_menu', 'menu.nama_menu')
            ->orderByDesc('total_terjual')
            ->limit(5);

        if ($request->dari && $request->sampai) {
            $query->whereBetween('penjualan.tanggal', [
                $request->dari,
                $request->sampai,
            ]);
        }

        return $query->get();
    }
}
