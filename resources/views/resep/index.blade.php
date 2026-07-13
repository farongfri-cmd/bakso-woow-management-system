@extends('layouts.app')

@section('content')

<style>
    .page-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .page-head h1 {
        font-size: 1.9rem;
        font-weight: 850;
        margin: 0;
    }

    .page-head p {
        color: var(--text-muted);
        margin: 0.35rem 0 0;
    }

    .data-panel {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .data-table {
        margin: 0;
    }

    .data-table th {
        color: var(--text-muted);
        font-size: 0.86rem;
        text-transform: uppercase;
        background: var(--surface-soft);
        border-bottom: 1px solid var(--line);
    }

    .data-table tbody tr {
        min-height: 66px;
    }

    .recipe-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .recipe-chip {
        display: inline-flex;
        align-items: center;
        min-height: 30px;
        border-radius: 8px;
        padding: 0.45rem 0.68rem;
        background: #fff7ed;
        color: #9a3412;
        font-size: 0.9rem;
        font-weight: 750;
    }

    .recipe-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .search-form {
        min-width: min(100%, 420px);
    }

    .empty-state {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
        padding: 2.5rem 1.5rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 2rem;
        line-height: 1;
        margin-bottom: 0.75rem;
    }

    @media (max-width: 720px) {
        .page-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .recipe-actions,
        .search-form {
            width: 100%;
        }
    }
</style>

<div class="page-head">
    <div>
        <h1>Resep</h1>
        <p>Kelola komposisi bahan baku untuk setiap menu.</p>
    </div>

    <div class="recipe-actions">
        <form action="/resep" method="GET" class="search-form">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama menu..."
                    value="{{ $search }}"
                >
                <button type="submit" class="btn btn-outline-primary">Cari</button>
                @if($search)
                    <a href="/resep" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <a href="/resep/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Resep
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($resep->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">&#128196;</div>
        <h5 class="fw-bold mb-1">Tidak ada resep ditemukan.</h5>
        <p class="text-muted mb-0">Silakan ubah kata kunci pencarian.</p>
    </div>
@else
    <div class="data-panel">
        <div class="table-responsive">
            <table class="table table-hover align-middle data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Menu</th>
                        <th>Status</th>
                        <th>Komposisi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resep as $item)
                    <tr>
                        <td>{{ $resep->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $item->menu->nama_menu }}</td>
                        <td>
                            @if($item->menu->status === 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="recipe-list">
                                @foreach($item->detailResep as $detail)
                                    <span class="recipe-chip">
                                        {{ $detail->bahanBaku->nama_bahan ?? 'Bahan terhapus' }}
                                        {{ rtrim(rtrim(number_format($detail->jumlah_pakai, 2), '0'), '.') }}
                                        {{ $detail->bahanBaku->satuan ?? '' }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="/resep/{{ $item->id_resep }}/edit" class="btn btn-warning btn-sm">Edit</a>

                            <form action="/resep/{{ $item->id_resep }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin mau hapus resep ini?')" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $resep->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection
