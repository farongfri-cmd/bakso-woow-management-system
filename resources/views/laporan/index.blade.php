@extends('layouts.app')

@section('content')
@php
    $periodeAktif = request('dari') && request('sampai')
        ? date('d-m-Y', strtotime(request('dari'))) . ' s/d ' . date('d-m-Y', strtotime(request('sampai')))
        : 'Semua periode';
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .laporan-page {
        display: grid;
        gap: 0.95rem;
    }

    .laporan-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
    }

    .laporan-title {
        margin: 0;
        font-weight: 850;
    }

    .laporan-period {
        color: var(--text-muted);
        margin: 0.35rem 0 0;
    }

    .laporan-period strong {
        color: var(--text-main);
    }

    .summary-card {
        position: relative;
        min-height: 156px;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
    }

    .summary-card .card-body {
        position: relative;
        z-index: 1;
        padding: 1.35rem 1.45rem 1.35rem 1.85rem;
    }

    .summary-card::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 5px;
        background: var(--accent);
    }

    .summary-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .summary-value {
        color: var(--text-main);
        font-size: clamp(1.55rem, 2.4vw, 2.15rem);
        font-weight: 850;
        line-height: 1.2;
        margin: 0;
        overflow-wrap: anywhere;
    }

    .summary-card .summary-value {
        padding-right: 0.5rem;
    }

    .summary-icon {
        display: inline-grid;
        place-items: center;
        width: 54px;
        height: 54px;
        border-radius: 8px;
        background: color-mix(in srgb, var(--accent) 12%, transparent);
        color: var(--accent);
        font-size: 1.5rem;
    }

    .laporan-card {
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
    }

    .laporan-card .card-body {
        padding: 1.25rem;
    }

    .top-menu-list {
        display: grid;
        gap: 0.7rem;
    }

    .top-menu-header {
        padding: 0.25rem 0.15rem 0;
    }

    .top-menu-title {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.35rem;
        font-weight: 850;
    }

    .top-menu-description {
        color: var(--text-muted);
        margin: 0;
    }

    .top-menu-item {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 0.85rem;
        align-items: center;
        min-height: 64px;
        padding: 0.85rem;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface-soft);
    }

    .top-menu-rank {
        display: inline-grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #fff3e8;
        color: var(--brand-dark);
        font-weight: 850;
    }

    .top-menu-name {
        font-weight: 800;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .laporan-table {
        margin-bottom: 0;
    }

    .laporan-table th {
        font-size: 0.86rem;
        white-space: nowrap;
    }

    .laporan-table td {
        vertical-align: middle;
    }

    .laporan-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.2rem;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
    }

    @media (max-width: 767.98px) {
        .laporan-header,
        .laporan-total {
            align-items: flex-start;
            flex-direction: column;
        }

        .top-menu-item {
            grid-template-columns: auto minmax(0, 1fr);
        }

        .top-menu-sales {
            grid-column: 2;
            justify-self: start;
        }
    }
</style>

<div class="laporan-page">
<div class="laporan-header">
    <div>
        <h2 class="laporan-title">Laporan Penjualan</h2>
        <p class="laporan-period">Periode aktif: <strong>{{ $periodeAktif }}</strong></p>
    </div>
</div>

<form method="GET" class="row g-2 align-items-end mb-2">
    <input type="hidden" name="search" value="{{ request('search') }}">

    <div class="col-auto">
        <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
    </div>
    <div class="col-auto">
        <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="row g-3 mb-2">
    <div class="col-12 col-md-4">
        <div class="summary-card h-100" style="--accent:#16a34a">
            <div class="card-body">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <p class="summary-label">Total Pendapatan</p>
                        <h3 class="summary-value">Rp {{ number_format($summary->total_pendapatan ?? 0) }}</h3>
                    </div>
                    <span class="summary-icon"><i class="bi bi-cash-stack"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="summary-card h-100" style="--accent:#2563eb">
            <div class="card-body">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <p class="summary-label">Total Transaksi</p>
                        <h3 class="summary-value">{{ number_format($summary->total_transaksi ?? 0) }} Transaksi</h3>
                    </div>
                    <span class="summary-icon"><i class="bi bi-receipt"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="summary-card h-100" style="--accent:#f97316">
            <div class="card-body">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <p class="summary-label">Produk Terjual</p>
                        <h3 class="summary-value">{{ number_format($summary->produk_terjual ?? 0) }} Item</h3>
                    </div>
                    <span class="summary-icon"><i class="bi bi-basket"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="laporan-card mb-2">
    <div class="card-body">
        <div class="top-menu-header mb-3">
            <h5 class="top-menu-title">
                <i class="bi bi-trophy text-warning"></i>
                <span>Top 5 Menu Terlaris</span>
            </h5>
            <p class="top-menu-description">Berdasarkan jumlah produk terjual pada periode aktif.</p>
        </div>

        @if($topMenu->count())
            <div class="top-menu-list">
                @foreach($topMenu as $menu)
                    <div class="top-menu-item">
                        <span class="top-menu-rank">{{ $loop->iteration }}</span>
                        <div class="min-w-0">
                            <div class="top-menu-name">{{ $menu->nama_menu }}</div>
                            <div class="text-muted small">Rp {{ number_format($menu->total_pendapatan) }}</div>
                        </div>
                        <div class="top-menu-sales fw-bold">{{ number_format($menu->total_terjual) }} Item</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-cup-hot fs-2 d-block mb-2"></i>
                Belum ada menu terjual pada periode ini.
            </div>
        @endif
    </div>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
    <form method="GET" class="row g-2 flex-grow-1">
        <input type="hidden" name="dari" value="{{ request('dari') }}">
        <input type="hidden" name="sampai" value="{{ request('sampai') }}">

        <div class="col-12 col-md-7 col-lg-5">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama menu...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>

    <a href="{{ url('/laporan/excel') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i>Download Excel
    </a>
</div>

@if($laporan->count())
    <div class="table-responsive laporan-card">
        <table class="table table-hover table-striped laporan-table">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th class="text-end">Jumlah</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($laporan as $i => $l)
                <tr>
                    <td class="fw-semibold">{{ $laporan->firstItem() + $i }}</td>
                    <td class="text-nowrap">{{ date('d-m-Y H:i', strtotime($l->tanggal)) }}</td>
                    <td>{{ $l->nama_menu }}</td>
                    <td class="text-end">{{ $l->jumlah }}</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($l->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $laporan->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="laporan-card mb-3">
        <div class="card-body text-center py-5">
            <i class="bi bi-file-earmark-text display-5 text-muted d-block mb-2"></i>
            <h5 class="fw-bold mb-2">Tidak ada data laporan.</h5>
            <p class="text-muted mb-0">Silakan ubah filter tanggal atau kata kunci pencarian.</p>
        </div>
    </div>
@endif

<hr>

<div class="laporan-total">
    <div>
        <div class="text-muted fw-semibold">Total Pendapatan</div>
        <h4 class="mb-0 fw-bold">Rp {{ number_format($total_semua) }}</h4>
    </div>
    <i class="bi bi-cash-coin fs-3 text-success"></i>
</div>

<hr class="my-4">

<div class="d-flex justify-content-between align-items-end gap-3 mb-3">
    <div>
        <h2 class="mb-1">Laporan Stok Bahan Baku</h2>
        <p class="text-muted mb-0">
            Data stok real-time per {{ now()->format('d-m-Y H:i') }}
        </p>
    </div>
</div>

<div class="table-responsive laporan-card">
    <table class="table table-hover table-striped laporan-table">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th class="text-end">Stok Saat Ini</th>
                <th>Satuan</th>
                <th>Terakhir Diupdate</th>
            </tr>
        </thead>

        <tbody>
            @forelse($stokBahanBaku as $i => $bahan)
            <tr>
                <td class="fw-semibold">{{ $i+1 }}</td>
                <td>{{ $bahan->nama_bahan }}</td>
                <td class="text-end">{{ rtrim(rtrim(number_format($bahan->stok, 2), '0'), '.') }}</td>
                <td>{{ $bahan->satuan }}</td>
                <td class="text-nowrap">{{ $bahan->updated_at ? $bahan->updated_at->format('d-m-Y H:i') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Data stok bahan baku belum ada</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

@endsection
