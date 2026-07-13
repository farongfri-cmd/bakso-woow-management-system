<?php

namespace App\Services;

use App\Models\DetailPenjualan;
use App\Models\Menu;
use App\Models\Penjualan;
use App\Support\DashboardFormatter;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(private DashboardFormatter $formatter)
    {
    }

    /**
     * Get dashboard data.
     */
    public function getDashboardData(): array
    {
        Carbon::setLocale('id');

        $now = Carbon::now();
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();

        $pendapatanHariIni = Penjualan::query()
            ->whereBetween('tanggal', [$todayStart, $todayEnd])
            ->sum('total');

        $transaksiHariIni = Penjualan::query()
            ->whereBetween('tanggal', [$todayStart, $todayEnd])
            ->count();

        $menuTerjualHariIni = DetailPenjualan::query()
            ->whereHas('penjualan', function ($query) use ($todayStart, $todayEnd) {
                $query->whereBetween('tanggal', [$todayStart, $todayEnd]);
            })
            ->sum('jumlah');

        $totalMenu = Menu::query()->count();

        $chartData = [
            'today' => $this->chartSalesData($todayStart, $todayEnd),
            '7days' => $this->chartSalesData($now->copy()->subDays(6)->startOfDay(), $todayEnd),
            '30days' => $this->chartSalesData($now->copy()->subDays(29)->startOfDay(), $todayEnd),
            'year' => $this->chartSalesData($now->copy()->startOfYear(), $todayEnd),
        ];

        $menuTerlaris = DetailPenjualan::query()
            ->select('id_menu')
            ->selectRaw('SUM(jumlah) as total_terjual')
            ->with('menu:id_menu,nama_menu')
            ->whereHas('penjualan', function ($query) use ($todayStart, $todayEnd) {
                $query->whereBetween('tanggal', [$todayStart, $todayEnd]);
            })
            ->groupBy('id_menu')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $penggunaanBahan = DetailPenjualan::query()
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('resep', 'detail_penjualan.id_menu', '=', 'resep.id_menu')
            ->join('detail_resep', 'resep.id_resep', '=', 'detail_resep.id_resep')
            ->join('bahan_baku', 'detail_resep.id_bahan', '=', 'bahan_baku.id_bahan')
            ->whereBetween('penjualan.tanggal', [$todayStart, $todayEnd])
            ->selectRaw('bahan_baku.id_bahan, bahan_baku.nama_bahan, bahan_baku.satuan')
            ->selectRaw('SUM(detail_penjualan.jumlah * detail_resep.jumlah_pakai) as total_pakai')
            ->groupBy('bahan_baku.id_bahan', 'bahan_baku.nama_bahan', 'bahan_baku.satuan')
            ->orderByDesc('total_pakai')
            ->limit(5)
            ->get();

        $transaksiTerbaru = Penjualan::query()
            ->with('user:id_user,nama')
            ->latest('tanggal')
            ->limit(5)
            ->get();

        $dashboardTime = [
            'sapaan' => $this->greeting($now),
            'hari' => $now->translatedFormat('l'),
            'tanggal' => $this->formatter->date($now),
            'jam' => $this->formatter->time($now),
        ];

        return compact(
            'pendapatanHariIni',
            'transaksiHariIni',
            'menuTerjualHariIni',
            'totalMenu',
            'chartData',
            'menuTerlaris',
            'penggunaanBahan',
            'transaksiTerbaru',
            'dashboardTime'
        );
    }

    private function chartSalesData(Carbon $startDate, Carbon $endDate): Collection
    {
        return Penjualan::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('DATE(tanggal) as tanggal')
            ->selectRaw('SUM(total) as total')
            ->groupByRaw('DATE(tanggal)')
            ->orderByRaw('DATE(tanggal)')
            ->get()
            ->map(function ($item) {
                $tanggal = Carbon::parse($item->tanggal);

                return [
                    'tanggal' => $this->formatter->date($tanggal),
                    'total' => (float) $item->total,
                ];
            });
    }

    private function greeting(Carbon $now): string
    {
        $hour = (int) $now->format('H');

        return match (true) {
            $hour < 11 => 'Selamat Pagi',
            $hour < 15 => 'Selamat Siang',
            $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
    }
}
