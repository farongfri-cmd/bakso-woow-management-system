<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukService
{
    /**
     * Validate produk input.
     */
    public function validateProduk(Request $request): array
    {
        return $request->validate([
            'nama_produk' => ['required', 'string', 'min:3', 'max:100'],
            'harga' => ['required', 'numeric', 'min:1000'],
        ]);
    }

    /**
     * Store a new produk record.
     */
    public function store(array $validated): Produk
    {
        return Produk::create([
            'nama_produk' => $validated['nama_produk'],
            'harga' => $validated['harga'],
            'status' => 'aktif',
        ]);
    }

    /**
     * Update an existing produk record.
     */
    public function update(int|string $id, array $validated): Produk
    {
        $produk = Produk::findOrFail($id);

        $produk->update([
            'nama_produk' => $validated['nama_produk'],
            'harga' => $validated['harga'],
        ]);

        return $produk;
    }

    /**
     * Toggle produk status through the delete action.
     */
    public function destroy(int|string $id): void
    {
        $this->toggleStatus($id);
    }

    /**
     * Toggle produk active status.
     */
    public function toggleStatus(int|string $id): Produk
    {
        $produk = Produk::findOrFail($id);

        $produk->update([
            'status' => $produk->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return $produk;
    }
}
