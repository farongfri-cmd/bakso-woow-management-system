<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Services\ProdukService;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function __construct(private ProdukService $produkService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $produk = Produk::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_menu', 'like', "%{$search}%");
            })
            ->orderBy('nama_menu')
            ->paginate(10)
            ->withQueryString();

        return view('produk.index', compact('produk', 'search'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $validated = $this->produkService->validateProduk($request);

        $this->produkService->store($validated);

        return redirect('/produk')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->produkService->validateProduk($request);

        $this->produkService->update($id, $validated);

        return redirect('/produk')->with('success', 'Menu berhasil diupdate');
    }

    public function destroy($id)
    {
        $this->produkService->destroy($id);

        return redirect('/produk')->with('success', 'Status menu berhasil diubah');
    }

    public function toggleStatus($id)
    {
        $this->produkService->toggleStatus($id);

        return redirect('/produk')->with('success', 'Status menu berhasil diubah');
    }
}
