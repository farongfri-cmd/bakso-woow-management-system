<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Menu;
use App\Models\Resep;
use App\Services\RecipeService;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    public function __construct(private RecipeService $recipeService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $resep = Resep::with(['menu', 'detailResep.bahanBaku'])
            ->whereHas('menu')
            ->when($search, function ($query, $search) {
                $query->whereHas('menu', function ($menuQuery) use ($search) {
                    $menuQuery->where('nama_menu', 'like', "%{$search}%");
                });
            })
            ->orderBy(
                Menu::select('nama_menu')
                    ->whereColumn('menu.id_menu', 'resep.id_menu')
                    ->limit(1)
            )
            ->paginate(10)
            ->withQueryString();

        return view('resep.index', compact('resep', 'search'));
    }

    public function create()
    {
        $menu = Menu::aktif()
            ->whereDoesntHave('resep')
            ->orderBy('nama_menu')
            ->get();
        $bahanBaku = BahanBaku::orderBy('nama_bahan')->get();

        return view('resep.create', compact('menu', 'bahanBaku'));
    }

    public function store(Request $request)
    {
        $validated = $this->recipeService->validateResep($request);

        $this->recipeService->store($validated);

        return redirect('/resep')->with('success', 'Resep berhasil ditambahkan');
    }

    public function edit($id)
    {
        $resep = Resep::with(['menu', 'detailResep.bahanBaku'])->findOrFail($id);
        $bahanBaku = BahanBaku::orderBy('nama_bahan')->get();

        return view('resep.edit', compact('resep', 'bahanBaku'));
    }

    public function update(Request $request, $id)
    {
        $resep = Resep::findOrFail($id);
        $validated = $this->recipeService->validateResep($request, isUpdate: true);

        $this->recipeService->update($resep, $validated);

        return redirect('/resep')->with('success', 'Resep berhasil diupdate');
    }

    public function destroy($id)
    {
        $this->recipeService->destroy($id);

        return redirect('/resep')->with('success', 'Resep berhasil dihapus');
    }
}
