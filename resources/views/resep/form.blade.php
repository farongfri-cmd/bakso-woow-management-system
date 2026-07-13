<style>
    .recipe-row {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(140px, 0.7fr) auto;
        gap: 0.9rem;
        align-items: end;
        padding: 0.85rem;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface-soft);
    }

    @media (max-width: 720px) {
        .recipe-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 fw-bold mb-1">{{ $title }}</h1>
                <p class="text-muted mb-4">{{ $subtitle }}</p>

                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ $action }}" method="POST">
                    @csrf
                    @if($method !== 'POST')
                        @method($method)
                    @endif

                    @if($method === 'POST')
                        <div class="mb-3">
                            <label class="form-label">Pilih Menu</label>
                            <select name="id_menu" class="form-select @error('id_menu') is-invalid @enderror" required>
                                <option value="">Pilih menu</option>
                                @foreach($menuOptions as $menu)
                                    <option value="{{ $menu->id_menu }}" {{ (string) $selectedMenuId === (string) $menu->id_menu ? 'selected' : '' }}>
                                        {{ $menu->nama_menu }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label">Nama Menu</label>
                            <input type="text" class="form-control" value="{{ $readonlyMenuName }}" readonly>
                        </div>
                    @endif

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <label class="form-label mb-0">Komposisi Bahan</label>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addRecipeRow">
                                <i class="bi bi-plus-lg"></i>
                                Tambah Baris
                            </button>
                        </div>

                        <div id="recipeRows" class="d-grid gap-3">
                            @foreach($details as $index => $detail)
                                <div class="recipe-row">
                                    <div>
                                        <label class="form-label">Bahan Baku</label>
                                        <select name="bahan[{{ $index }}][id_bahan]" class="form-select" required>
                                            <option value="">Pilih bahan</option>
                                            @foreach($bahanBaku as $bahan)
                                                <option value="{{ $bahan->id_bahan }}" {{ (string) ($detail['id_bahan'] ?? '') === (string) $bahan->id_bahan ? 'selected' : '' }}>
                                                    {{ $bahan->nama_bahan }} ({{ $bahan->satuan }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label">Jumlah Pakai</label>
                                        <input type="number" step="0.01" min="0.01" name="bahan[{{ $index }}][jumlah_pakai]" class="form-control" value="{{ $detail['jumlah_pakai'] ?? '' }}" required>
                                    </div>

                                    <button type="button" class="btn btn-outline-danger remove-recipe-row">Hapus</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="/resep" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="recipeRowTemplate">
    <div class="recipe-row">
        <div>
            <label class="form-label">Bahan Baku</label>
            <select class="form-select js-bahan" required>
                <option value="">Pilih bahan</option>
                @foreach($bahanBaku as $bahan)
                    <option value="{{ $bahan->id_bahan }}">{{ $bahan->nama_bahan }} ({{ $bahan->satuan }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Jumlah Pakai</label>
            <input type="number" step="0.01" min="0.01" class="form-control js-jumlah" required>
        </div>

        <button type="button" class="btn btn-outline-danger remove-recipe-row">Hapus</button>
    </div>
</template>

<script>
    const recipeRows = document.getElementById('recipeRows');
    const addRecipeRow = document.getElementById('addRecipeRow');
    const recipeRowTemplate = document.getElementById('recipeRowTemplate');

    function refreshRecipeRowNames() {
        recipeRows.querySelectorAll('.recipe-row').forEach((row, index) => {
            row.querySelector('select').name = `bahan[${index}][id_bahan]`;
            row.querySelector('input').name = `bahan[${index}][jumlah_pakai]`;
        });
    }

    addRecipeRow.addEventListener('click', () => {
        const row = recipeRowTemplate.content.cloneNode(true);
        recipeRows.appendChild(row);
        refreshRecipeRowNames();
    });

    recipeRows.addEventListener('click', (event) => {
        if (!event.target.classList.contains('remove-recipe-row')) {
            return;
        }

        if (recipeRows.querySelectorAll('.recipe-row').length === 1) {
            return;
        }

        event.target.closest('.recipe-row').remove();
        refreshRecipeRowNames();
    });

    refreshRecipeRowNames();
</script>
