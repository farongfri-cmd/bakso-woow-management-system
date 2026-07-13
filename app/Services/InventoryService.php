<?php

namespace App\Services;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    /**
     * Validate bahan baku input.
     */
    public function validateBahanBaku(Request $request, bool $includeStok = false): array
    {
        $rules = [
            'nama_bahan' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
        ];

        if ($includeStok) {
            $rules['stok'] = ['required', 'numeric', 'min:0'];
        }

        return $request->validate($rules);
    }

    /**
     * Store a new bahan baku record.
     */
    public function store(array $validated): BahanBaku
    {
        return BahanBaku::create([
            'nama_bahan' => $validated['nama_bahan'],
            'satuan' => $validated['satuan'],
            'stok' => $validated['stok'],
            'status' => 'aktif',
        ]);
    }

    /**
     * Update an existing bahan baku record.
     */
    public function update(int|string $id, array $validated): BahanBaku
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        $bahanBaku->update($validated);

        return $bahanBaku;
    }

    /**
     * Toggle bahan baku status through the delete action.
     */
    public function destroy(int|string $id): void
    {
        $this->toggleStatus($id);
    }

    /**
     * Toggle bahan baku active status.
     */
    public function toggleStatus(int|string $id): BahanBaku
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        $bahanBaku->update([
            'status' => $bahanBaku->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return $bahanBaku;
    }

    /**
     * Save stock adjustment for bahan baku.
     */
    public function simpanPenyesuaianStok(Request $request, int|string $id): void
    {
        $validated = $request->validate([
            'jenis_perubahan' => ['required', 'in:tambah,kurang'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($id, $validated) {
            $bahanBaku = BahanBaku::lockForUpdate()->findOrFail($id);
            $jumlah = (float) $validated['jumlah'];

            if ($validated['jenis_perubahan'] === 'kurang') {
                if ((float) $bahanBaku->stok < $jumlah) {
                    throw new RuntimeException('Stok bahan baku tidak mencukupi.');
                }

                $bahanBaku->kurangiStok($jumlah, $validated['keterangan']);
                return;
            }

            $bahanBaku->koreksiStok($jumlah, $validated['keterangan']);
        });
    }
}
