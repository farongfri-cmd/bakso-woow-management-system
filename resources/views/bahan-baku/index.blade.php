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

    .stock-badge {
        display: inline-flex;
        min-width: 104px;
        min-height: 30px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        padding: 0.45rem 0.7rem;
        background: #ecfdf5;
        color: #047857;
        font-weight: 800;
        font-size: 0.9rem;
    }

    @media (max-width: 720px) {
        .page-head {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="page-head">
    <div>
        <h1>Bahan Baku</h1>
        <p>Kelola stok bahan baku untuk resep dan penjualan.</p>
    </div>

    <a href="/bahan-baku/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Tambah Bahan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="/bahan-baku" method="GET" class="mb-3">
    <div class="input-group">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Cari nama bahan..."
            value="{{ $search }}"
        >
        <button type="submit" class="btn btn-outline-primary">Cari</button>
        @if($search)
            <a href="/bahan-baku" class="btn btn-outline-secondary">Reset</a>
        @endif
    </div>
</form>

<div class="data-panel">
    <div class="table-responsive">
        <table class="table table-hover align-middle data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Satuan</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahanBaku as $bahan)
                <tr>
                    <td>{{ $bahanBaku->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $bahan->nama_bahan }}</td>
                    <td>{{ $bahan->satuan }}</td>
                    <td>
                        <span class="stock-badge">
                            {{ rtrim(rtrim(number_format($bahan->stok, 2), '0'), '.') }}
                        </span>
                    </td>
                    <td>
                        @if($bahan->status === 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="/bahan-baku/{{ $bahan->id_bahan }}/edit" class="btn btn-warning btn-sm">Edit</a>
                        <a href="/bahan-baku/{{ $bahan->id_bahan }}/penyesuaian-stok" class="btn btn-outline-primary btn-sm">
                            Penyesuaian Stok
                        </a>

                        <form action="/bahan-baku/{{ $bahan->id_bahan }}/status" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Yakin ingin mengubah status bahan ini?')" class="btn {{ $bahan->status === 'aktif' ? 'btn-outline-danger' : 'btn-outline-success' }} btn-sm">
                                {{ $bahan->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Data bahan baku belum ada</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $bahanBaku->links('pagination::bootstrap-5') }}
</div>

@endsection
