<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Penjualan;
use App\Models\StokLog;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SalesService
{
    private const INSUFFICIENT_STOCK_MESSAGE = 'Stok bahan baku tidak mencukupi.';

    /**
     * Validate transaction item input.
     */
    public function validateItems(Request $request): array
    {
        return $request->validate([
            'menu_id' => ['required', 'array', 'min:1'],
            'menu_id.*' => ['required', 'exists:menu,id_menu'],
            'jumlah' => ['required', 'array', 'min:1'],
            'jumlah.*' => ['required', 'integer', 'min:1'],
        ]);
    }

    /**
     * Store a new sales transaction.
     */
    public function store(array $validated): Penjualan
    {
        return DB::transaction(function () use ($validated) {
            $penjualan = Penjualan::create([
                'tanggal' => now(),
                'total' => 0,
                'id_user' => auth()->id(),
            ]);

            $detailPenjualan = $this->syncDetailPenjualan(
                $penjualan,
                $validated['menu_id'],
                $validated['jumlah']
            );

            $this->kurangiStokBahanBaku($penjualan, $detailPenjualan);

            return $penjualan;
        });
    }

    /**
     * Update sales transaction details.
     */
    public function update(Penjualan $penjualan, array $validated): void
    {
        DB::transaction(function () use ($penjualan, $validated) {
            $this->syncDetailPenjualan($penjualan, $validated['menu_id'], $validated['jumlah']);
        });
    }

    /**
     * Delete a sales transaction and restore stock.
     */
    public function destroy(Penjualan $penjualan): void
    {
        DB::transaction(function () use ($penjualan) {
            $this->kembalikanStokBahanBaku($penjualan);
            $this->hapusStokLogTransaksi($penjualan);

            $penjualan->detailPenjualan()->delete();
            $penjualan->delete();
        });
    }

    private function syncDetailPenjualan(Penjualan $penjualan, array $menuIds, array $jumlahList): Collection
    {
        $penjualan->detailPenjualan()->delete();

        $menus = Menu::with('resep.detailResep')
            ->whereIn('id_menu', $menuIds)
            ->get()
            ->keyBy('id_menu');

        $detailPenjualan = collect();
        $total = 0;

        foreach ($menuIds as $index => $id_menu) {
            $menu = $menus->get($id_menu);
            $jumlah = (int) ($jumlahList[$index] ?? 0);

            if (!$menu || $jumlah <= 0) {
                continue;
            }

            $subtotal = $menu->harga * $jumlah;
            $total += $subtotal;

            $detail = $penjualan->detailPenjualan()->create([
                'id_menu' => $menu->id_menu,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
            ]);

            $detail->setRelation('menu', $menu);
            $detailPenjualan->push($detail);
        }

        $penjualan->update(['total' => $total]);

        return $detailPenjualan;
    }

    private function kurangiStokBahanBaku(Penjualan $penjualan, Collection $detailPenjualan): void
    {
        foreach ($detailPenjualan as $detail) {
            $resep = $detail->menu->resep;

            if (!$resep) {
                continue;
            }

            foreach ($resep->detailResep as $detailResep) {
                $bahanBaku = $detailResep->bahanBaku()->lockForUpdate()->first();
                $jumlahDipakai = (float) $detail->jumlah * (float) $detailResep->jumlah_pakai;

                if (!$bahanBaku || (float) $bahanBaku->stok < $jumlahDipakai) {
                    throw new RuntimeException(self::INSUFFICIENT_STOCK_MESSAGE);
                }

                $keterangan = "Penjualan #{$penjualan->id_penjualan} - {$detail->menu->nama_menu}";
                $bahanBaku->kurangiStok($jumlahDipakai, $keterangan);
            }
        }
    }

    private function kembalikanStokBahanBaku(Penjualan $penjualan): void
    {
        foreach ($penjualan->detailPenjualan as $detail) {
            $resep = $detail->menu->resep;

            if (!$resep) {
                continue;
            }

            foreach ($resep->detailResep as $detailResep) {
                $bahanBaku = $detailResep->bahanBaku()->lockForUpdate()->first();

                if (!$bahanBaku) {
                    throw new RuntimeException('Bahan baku resep tidak ditemukan.');
                }

                $jumlahDikembalikan = (float) $detail->jumlah * (float) $detailResep->jumlah_pakai;
                $keterangan = "Pembatalan Penjualan #{$penjualan->id_penjualan} - {$detail->menu->nama_menu}";

                $bahanBaku->koreksiStok($jumlahDikembalikan, $keterangan);
            }
        }
    }

    private function hapusStokLogTransaksi(Penjualan $penjualan): void
    {
        StokLog::where('keterangan', 'like', "%Penjualan #{$penjualan->id_penjualan} -%")->delete();
    }
}
