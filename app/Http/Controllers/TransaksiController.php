<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Penjualan;
use App\Services\SalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function __construct(private SalesService $salesService)
    {
    }

    public function index()
    {
        $menu = Menu::aktif()->orderBy('nama_menu')->get();

        return view('transaksi.index', compact('menu'));
    }

    public function store(Request $request)
    {
        $validated = $this->salesService->validateItems($request);

        try {
            $penjualan = $this->salesService->store($validated);

            return redirect("/transaksi/{$penjualan->id_penjualan}/detail")
                ->with('success', 'Transaksi berhasil!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function detail($id)
    {
        $transaksi = DB::table('penjualan')
            ->where('id_penjualan', $id)
            ->first();

        abort_if(!$transaksi, 404);

        $detail = DB::table('detail_penjualan')
            ->join('menu', 'detail_penjualan.id_menu', '=', 'menu.id_menu')
            ->where('id_penjualan', $id)
            ->select(
                'detail_penjualan.id_detail',
                'detail_penjualan.id_menu',
                'menu.nama_menu',
                'menu.harga',
                'detail_penjualan.jumlah',
                'detail_penjualan.subtotal'
            )
            ->get();

        $menu = Menu::aktif()->orderBy('nama_menu')->get();

        return view('transaksi.detail', compact('transaksi', 'detail', 'menu'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->salesService->validateItems($request);
        $penjualan = Penjualan::find($id);

        abort_if(!$penjualan, 404);

        try {
            $this->salesService->update($penjualan, $validated);

            return redirect("/transaksi/$id/detail")
                ->with('success', 'Detail transaksi berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.menu.resep.detailResep')->find($id);

        abort_if(!$penjualan, 404);

        try {
            $this->salesService->destroy($penjualan);

            return redirect('/transaksi')
                ->with('success', 'Transaksi berhasil dibatalkan.')
                ->with('cancel_success_title', 'Transaksi berhasil dibatalkan.')
                ->with('cancel_success_text', 'Stok bahan baku telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function struk($id)
    {
        $transaksi = DB::table('penjualan')
            ->where('id_penjualan', $id)
            ->first();

        $detail = DB::table('detail_penjualan')
            ->join('menu', 'detail_penjualan.id_menu', '=', 'menu.id_menu')
            ->where('id_penjualan', $id)
            ->select(
                'menu.nama_menu',
                'detail_penjualan.jumlah',
                'detail_penjualan.subtotal'
            )
            ->get();

        return view('transaksi.struk', compact('transaksi', 'detail'));
    }
}
