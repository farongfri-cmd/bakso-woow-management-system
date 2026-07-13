<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function __construct(private InventoryService $inventoryService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $bahanBaku = BahanBaku::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_bahan', 'like', "%{$search}%");
            })
            ->orderBy('nama_bahan')
            ->paginate(10)
            ->withQueryString();

        return view('bahan-baku.index', compact('bahanBaku', 'search'));
    }

    public function create()
    {
        return view('bahan-baku.create');
    }

    public function store(Request $request)
    {
        $validated = $this->inventoryService->validateBahanBaku($request, includeStok: true);

        $this->inventoryService->store($validated);

        return redirect('/bahan-baku')->with('success', 'Bahan baku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);

        return view('bahan-baku.edit', compact('bahanBaku'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->inventoryService->validateBahanBaku($request);

        $this->inventoryService->update($id, $validated);

        return redirect('/bahan-baku')->with('success', 'Bahan baku berhasil diupdate');
    }

    public function penyesuaianStok($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);

        return view('bahan-baku.penyesuaian-stok', compact('bahanBaku'));
    }

    public function simpanPenyesuaianStok(Request $request, $id)
    {
        try {
            $this->inventoryService->simpanPenyesuaianStok($request, $id);
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect('/bahan-baku')->with('success', 'Penyesuaian stok berhasil disimpan');
    }

    public function destroy($id)
    {
        $this->inventoryService->destroy($id);

        return redirect('/bahan-baku')->with('success', 'Status bahan baku berhasil diubah');
    }

    public function toggleStatus($id)
    {
        $this->inventoryService->toggleStatus($id);

        return redirect('/bahan-baku')->with('success', 'Status bahan baku berhasil diubah');
    }
}
