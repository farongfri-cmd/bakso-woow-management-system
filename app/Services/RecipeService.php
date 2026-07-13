<?php

namespace App\Services;

use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RecipeService
{
    /**
     * Validate resep input.
     */
    public function validateResep(Request $request, bool $isUpdate = false): array
    {
        $rules = [
            'bahan' => ['required', 'array', 'min:1'],
            'bahan.*.id_bahan' => ['required', 'distinct', 'exists:bahan_baku,id_bahan'],
            'bahan.*.jumlah_pakai' => ['required', 'numeric', 'min:0.01'],
        ];

        if (!$isUpdate) {
            $rules = [
                'id_menu' => ['required', 'exists:menu,id_menu', Rule::unique('resep', 'id_menu')],
            ] + $rules;
        }

        return $request->validate($rules, [
            'id_menu.required' => 'Menu wajib dipilih.',
            'id_menu.exists' => 'Menu yang dipilih tidak valid.',
            'id_menu.unique' => 'Menu ini sudah memiliki resep.',
            'bahan.*.id_bahan.distinct' => 'Bahan baku tidak boleh dipilih lebih dari sekali.',
        ]);
    }

    /**
     * Store a new resep record with detail resep.
     */
    public function store(array $validated): void
    {
        DB::transaction(function () use ($validated) {
            $resep = Resep::create([
                'id_menu' => $validated['id_menu'],
            ]);

            $this->syncDetailResep($resep, $validated['bahan']);
        });
    }

    /**
     * Update detail resep for an existing resep record.
     */
    public function update(Resep $resep, array $validated): void
    {
        DB::transaction(function () use ($resep, $validated) {
            $this->syncDetailResep($resep, $validated['bahan']);
        });
    }

    /**
     * Delete an existing resep record.
     */
    public function destroy(int|string $id): void
    {
        $resep = Resep::findOrFail($id);
        $resep->delete();
    }

    private function syncDetailResep(Resep $resep, array $bahan): void
    {
        $resep->detailResep()->delete();

        foreach ($bahan as $item) {
            $resep->detailResep()->create([
                'id_bahan' => $item['id_bahan'],
                'jumlah_pakai' => $item['jumlah_pakai'],
            ]);
        }
    }
}
