@extends('layouts.app')

@section('content')
@php
    $format = app(\App\Support\DashboardFormatter::class);
    $user = auth()->user();
    $role = $user ? ucfirst($user->role) : 'Pengguna';
    $menuTeratas = $menuTerlaris->first();
    $bahanTeratas = $penggunaanBahan->first();
@endphp

<script>document.documentElement.classList.add('dashboard-loading');</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .dashboard-page {
        display: grid;
        gap: 1.05rem;
    }

    .min-w-0 {
        min-width: 0;
    }

    .dashboard-skeleton {
        position: absolute;
        inset: 0;
        z-index: 3;
        display: none;
        padding: 1.35rem;
        background: var(--surface);
        pointer-events: none;
    }

    .dashboard-loading .dashboard-skeleton {
        display: block;
    }

    .dashboard-loading .kpi-card > :not(.dashboard-skeleton),
    .dashboard-loading .dashboard-card > :not(.dashboard-skeleton) {
        opacity: 0;
    }

    .skeleton-line {
        display: block;
        min-height: 0.85rem;
        border-radius: 8px;
    }

    .skeleton-box {
        display: block;
        width: 100%;
        border-radius: 8px;
    }

    .skeleton-chart {
        height: 300px;
    }

    .dashboard-hero {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        padding-bottom: 0.15rem;
    }

    .dashboard-eyebrow {
        color: var(--brand);
        font-size: 0.78rem;
        font-weight: 800;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
    }

    .dashboard-title {
        margin: 0;
        font-size: clamp(1.65rem, 3vw, 2.35rem);
        font-weight: 850;
    }

    .dashboard-subtitle {
        color: var(--text-muted);
        margin: 0.35rem 0 0;
    }

    .dashboard-clock {
        min-width: 210px;
        padding: 1rem 1.15rem;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
        text-align: right;
    }

    .dashboard-clock-day {
        font-weight: 850;
    }

    .dashboard-clock-date {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .dashboard-clock-time {
        color: var(--brand);
        font-size: 1.35rem;
        font-weight: 900;
        line-height: 1.15;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.05rem;
    }

    .kpi-card,
    .dashboard-card {
        border: 1px solid var(--line);
        border-radius: 8px;
        background: var(--surface);
        box-shadow: var(--shadow-sm);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        will-change: transform;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
        border-color: var(--line);
        box-shadow: 0 16px 38px rgba(15, 23, 42, 0.12);
    }

    .kpi-card {
        position: relative;
        min-height: 180px;
        padding: 1.35rem 1.4rem;
        overflow: hidden;
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        border-color: color-mix(in srgb, var(--accent) 28%, var(--line));
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.14);
    }

    .kpi-card::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 5px;
        background: var(--accent);
        transition: width 0.28s ease;
    }

    .kpi-card:hover::before {
        width: 7px;
    }

    .kpi-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .kpi-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        font-weight: 800;
        margin: 0;
    }

    .kpi-icon {
        display: inline-grid;
        place-items: center;
        width: 54px;
        height: 54px;
        border-radius: 8px;
        background: color-mix(in srgb, var(--accent) 14%, transparent);
        color: var(--accent);
        font-size: 1.55rem;
        transition: transform 0.28s ease, background-color 0.28s ease;
    }

    .kpi-card:hover .kpi-icon {
        transform: scale(1.06);
        background: color-mix(in srgb, var(--accent) 18%, transparent);
    }

    .kpi-value {
        margin: 1.15rem 0 0;
        font-size: clamp(1.85rem, 2.5vw, 2.55rem);
        line-height: 1.05;
        font-weight: 900;
        overflow-wrap: anywhere;
    }

    .kpi-note {
        color: var(--text-muted);
        margin-top: 0.55rem;
        font-size: 0.9rem;
    }

    .dashboard-card {
        position: relative;
        overflow: hidden;
        padding: 1.25rem;
    }

    .dashboard-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.85rem;
    }

    .dashboard-card-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
        font-size: 1.04rem;
        font-weight: 850;
    }

    .chart-filter {
        display: inline-flex;
        gap: 0.35rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .chart-filter .btn {
        min-height: 38px;
        font-size: 0.82rem;
        font-weight: 750;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, color 0.25s ease;
    }

    .chart-filter .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(249, 115, 22, 0.16);
    }

    .chart-wrap {
        position: relative;
        height: 360px;
    }

    .chart-wrap canvas {
        transition: opacity 0.25s ease;
    }

    .chart-wrap.is-empty canvas {
        opacity: 0;
    }

    .empty-state {
        color: var(--text-muted);
        padding: 1rem;
        text-align: center;
    }

    .dashboard-empty-state {
        display: grid;
        min-height: 190px;
        place-items: center;
        padding: 1.5rem;
        border: 1px dashed var(--line);
        border-radius: 8px;
        background: var(--surface-soft);
        color: var(--text-muted);
        text-align: center;
    }

    .dashboard-empty-icon {
        display: inline-grid;
        width: 46px;
        height: 46px;
        margin-bottom: 0.75rem;
        place-items: center;
        border-radius: 8px;
        background: color-mix(in srgb, var(--brand) 13%, transparent);
        color: var(--brand);
        font-size: 1.35rem;
    }

    .dashboard-empty-title {
        margin: 0;
        color: var(--text-main);
        font-size: 0.98rem;
        font-weight: 850;
    }

    .dashboard-empty-description {
        max-width: 320px;
        margin: 0.35rem auto 0;
        font-size: 0.86rem;
    }

    .empty-chart {
        position: absolute;
        inset: 0;
        display: none;
        place-items: center;
        color: var(--text-muted);
        text-align: center;
        pointer-events: none;
    }

    .chart-placeholder {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        gap: 0.55rem;
        padding: 1.25rem;
        border: 1px dashed var(--line);
        border-radius: 8px;
        background: var(--surface-soft);
    }

    .chart-placeholder i {
        color: var(--brand);
        font-size: 1.75rem;
    }

    .rank-list,
    .usage-list {
        display: grid;
        gap: 0.8rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .rank-item,
    .usage-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0 0.25rem 0.85rem;
        border-bottom: 1px solid var(--line);
        border-radius: 8px;
        transition: transform 0.25s ease, background-color 0.25s ease, border-color 0.25s ease;
    }

    .rank-item:hover,
    .usage-item:hover {
        transform: translateX(3px);
        background: color-mix(in srgb, var(--brand) 7%, transparent);
    }

    .rank-item:last-child,
    .usage-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .rank-meta,
    .usage-meta {
        min-width: 0;
    }

    .rank-name,
    .usage-name {
        margin: 0;
        font-weight: 800;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .rank-note,
    .usage-note {
        color: var(--text-muted);
        margin: 0.1rem 0 0;
        font-size: 0.82rem;
    }

    .table-dashboard {
        margin: 0;
        vertical-align: middle;
    }

    .table-dashboard tbody tr {
        transition: background-color 0.25s ease, transform 0.25s ease;
    }

    .table-dashboard tbody tr:hover {
        background-color: color-mix(in srgb, var(--brand) 7%, transparent);
        transform: translateX(2px);
    }

    .table-dashboard th {
        color: var(--text-muted);
        font-size: 0.84rem;
        font-weight: 850;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .operation-alert {
        margin: 0;
        border: 1px solid rgba(13, 110, 253, 0.16);
        background: rgba(13, 110, 253, 0.08);
        color: var(--text-main);
    }

    @media (max-width: 1199.98px) {
        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .dashboard-hero {
            align-items: stretch;
            flex-direction: column;
        }

        .dashboard-clock {
            min-width: 0;
            text-align: left;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .chart-filter {
            justify-content: flex-start;
        }

        .chart-wrap {
            height: 300px;
        }
    }
</style>

<div class="dashboard-page">
    <section class="dashboard-hero">
        <div>
            <div class="dashboard-eyebrow">Ringkasan Operasional</div>
            <h1 class="dashboard-title">{{ $dashboardTime['sapaan'] }}, {{ $role }}</h1>
            <p class="dashboard-subtitle">Semoga operasional Bakso Woow berjalan lancar hari ini.</p>
        </div>

        <div class="dashboard-clock">
            <div class="dashboard-clock-day">{{ $dashboardTime['hari'] }}</div>
            <div class="dashboard-clock-date">{{ $dashboardTime['tanggal'] }}</div>
            <div class="dashboard-clock-time">{{ $dashboardTime['jam'] }}</div>
        </div>
    </section>

    <section class="kpi-grid">
        <article class="kpi-card" style="--accent:#16a34a">
            <div class="dashboard-skeleton" aria-hidden="true">
                <div class="placeholder-glow d-flex justify-content-between gap-3">
                    <span class="placeholder skeleton-line col-5"></span>
                    <span class="placeholder rounded-3" style="width:54px;height:54px;"></span>
                </div>
                <div class="placeholder-glow mt-4">
                    <span class="placeholder skeleton-line col-8" style="height:2rem;"></span>
                    <span class="placeholder skeleton-line col-6 mt-3"></span>
                </div>
            </div>
            <div class="kpi-top">
                <p class="kpi-label">Pendapatan Hari Ini</p>
                <span class="kpi-icon"><i class="bi bi-cash-stack"></i></span>
            </div>
            <div class="kpi-value">{{ $format->money($pendapatanHariIni) }}</div>
            <div class="kpi-note">Omzet penjualan hari ini.</div>
        </article>

        <article class="kpi-card" style="--accent:#2563eb">
            <div class="dashboard-skeleton" aria-hidden="true">
                <div class="placeholder-glow d-flex justify-content-between gap-3">
                    <span class="placeholder skeleton-line col-5"></span>
                    <span class="placeholder rounded-3" style="width:54px;height:54px;"></span>
                </div>
                <div class="placeholder-glow mt-4">
                    <span class="placeholder skeleton-line col-7" style="height:2rem;"></span>
                    <span class="placeholder skeleton-line col-6 mt-3"></span>
                </div>
            </div>
            <div class="kpi-top">
                <p class="kpi-label">Transaksi Hari Ini</p>
                <span class="kpi-icon"><i class="bi bi-receipt"></i></span>
            </div>
            <div class="kpi-value">{{ $format->number($transaksiHariIni) }}</div>
            <div class="kpi-note">Transaksi berhasil.</div>
        </article>

        <article class="kpi-card" style="--accent:#f97316">
            <div class="dashboard-skeleton" aria-hidden="true">
                <div class="placeholder-glow d-flex justify-content-between gap-3">
                    <span class="placeholder skeleton-line col-6"></span>
                    <span class="placeholder rounded-3" style="width:54px;height:54px;"></span>
                </div>
                <div class="placeholder-glow mt-4">
                    <span class="placeholder skeleton-line col-7" style="height:2rem;"></span>
                    <span class="placeholder skeleton-line col-6 mt-3"></span>
                </div>
            </div>
            <div class="kpi-top">
                <p class="kpi-label">Menu Terjual Hari Ini</p>
                <span class="kpi-icon"><i class="bi bi-basket"></i></span>
            </div>
            <div class="kpi-value">{{ $format->number($menuTerjualHariIni) }}</div>
            <div class="kpi-note">Porsi berhasil terjual.</div>
        </article>

        <article class="kpi-card" style="--accent:#7c3aed">
            <div class="dashboard-skeleton" aria-hidden="true">
                <div class="placeholder-glow d-flex justify-content-between gap-3">
                    <span class="placeholder skeleton-line col-5"></span>
                    <span class="placeholder rounded-3" style="width:54px;height:54px;"></span>
                </div>
                <div class="placeholder-glow mt-4">
                    <span class="placeholder skeleton-line col-7" style="height:2rem;"></span>
                    <span class="placeholder skeleton-line col-6 mt-3"></span>
                </div>
            </div>
            <div class="kpi-top">
                <p class="kpi-label">Total Menu</p>
                <span class="kpi-icon"><i class="bi bi-cup-hot"></i></span>
            </div>
            <div class="kpi-value">{{ $format->number($totalMenu) }}</div>
            <div class="kpi-note">Menu tersedia.</div>
        </article>
    </section>

    <section class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="dashboard-card h-100">
                <div class="dashboard-skeleton" aria-hidden="true">
                    <div class="placeholder-glow d-flex justify-content-between gap-3 mb-4">
                        <span class="placeholder skeleton-line col-4"></span>
                        <span class="placeholder skeleton-line col-3"></span>
                    </div>
                    <div class="placeholder-glow">
                        <span class="placeholder skeleton-box skeleton-chart"></span>
                    </div>
                </div>
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title">
                        <i class="bi bi-graph-up-arrow text-warning"></i>
                        Grafik Penjualan
                    </h2>

                    <div class="chart-filter" aria-label="Filter grafik penjualan">
                        <button type="button" class="btn btn-sm btn-warning text-white js-chart-filter active" data-range="today">Hari Ini</button>
                        <button type="button" class="btn btn-sm btn-outline-warning js-chart-filter" data-range="7days">7 Hari</button>
                        <button type="button" class="btn btn-sm btn-outline-warning js-chart-filter" data-range="30days">30 Hari</button>
                        <button type="button" class="btn btn-sm btn-outline-warning js-chart-filter" data-range="year">Tahun Ini</button>
                    </div>
                </div>

                <div class="chart-wrap">
                    <canvas id="salesChart"></canvas>
                    <div class="empty-chart" id="emptyChart">
                        <div class="chart-placeholder">
                            <i class="bi bi-bar-chart-line"></i>
                            <strong>Belum ada data penjualan pada periode ini.</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="dashboard-card h-100">
                <div class="dashboard-skeleton" aria-hidden="true">
                    <div class="placeholder-glow mb-4">
                        <span class="placeholder skeleton-line col-5"></span>
                    </div>
                    <div class="placeholder-glow d-grid gap-3">
                        <span class="placeholder skeleton-line col-12"></span>
                        <span class="placeholder skeleton-line col-10"></span>
                        <span class="placeholder skeleton-line col-11"></span>
                        <span class="placeholder skeleton-line col-9"></span>
                    </div>
                </div>
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title">
                        <i class="bi bi-trophy text-warning"></i>
                        Menu Terlaris
                    </h2>
                </div>

                @if($menuTerlaris->isEmpty())
                    <div class="dashboard-empty-state">
                        <div>
                            <span class="dashboard-empty-icon"><i class="bi bi-cup-hot"></i></span>
                            <h3 class="dashboard-empty-title">Belum ada menu terjual hari ini.</h3>
                            <p class="dashboard-empty-description">Daftar menu terlaris akan muncul setelah transaksi hari ini tercatat.</p>
                        </div>
                    </div>
                @else
                    <ol class="rank-list">
                        @foreach($menuTerlaris as $index => $item)
                            <li class="rank-item">
                                <div class="d-flex align-items-center gap-3 min-w-0">
                                    <span class="badge text-bg-warning">#{{ $index + 1 }}</span>
                                    <div class="rank-meta">
                                        <p class="rank-name">{{ $item->menu?->nama_menu ?? 'Menu tidak ditemukan' }}</p>
                                        <p class="rank-note">Total terjual</p>
                                    </div>
                                </div>
                                <span class="badge text-bg-light">{{ $format->number($item->total_terjual) }} porsi</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </section>

    <section class="row g-3">
        <div class="col-12 col-xl-5">
            <div class="dashboard-card h-100">
                <div class="dashboard-skeleton" aria-hidden="true">
                    <div class="placeholder-glow mb-4">
                        <span class="placeholder skeleton-line col-7"></span>
                    </div>
                    <div class="placeholder-glow d-grid gap-3">
                        <span class="placeholder skeleton-line col-12"></span>
                        <span class="placeholder skeleton-line col-10"></span>
                        <span class="placeholder skeleton-line col-11"></span>
                    </div>
                </div>
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title">
                        <i class="bi bi-box-seam text-warning"></i>
                        Penggunaan Bahan Baku Hari Ini
                    </h2>
                </div>

                @if($penggunaanBahan->isEmpty())
                    <div class="dashboard-empty-state">
                        <div>
                            <span class="dashboard-empty-icon"><i class="bi bi-box-seam"></i></span>
                            <h3 class="dashboard-empty-title">Belum ada penggunaan bahan baku hari ini.</h3>
                            <p class="dashboard-empty-description">Pemakaian bahan akan dihitung otomatis dari menu yang terjual.</p>
                        </div>
                    </div>
                @else
                    <ul class="usage-list">
                        @foreach($penggunaanBahan as $bahan)
                            <li class="usage-item">
                                <div class="usage-meta">
                                    <p class="usage-name">{{ $bahan->nama_bahan }}</p>
                                    <p class="usage-note">Berdasarkan resep menu terjual</p>
                                </div>
                                <span class="badge text-bg-warning">
                                    {{ $format->unit($bahan->total_pakai, $bahan->satuan) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="col-12 col-xl-7">
            <div class="dashboard-card h-100">
                <div class="dashboard-skeleton" aria-hidden="true">
                    <div class="placeholder-glow mb-4">
                        <span class="placeholder skeleton-line col-5"></span>
                    </div>
                    <div class="placeholder-glow d-grid gap-3">
                        <span class="placeholder skeleton-line col-12"></span>
                        <span class="placeholder skeleton-line col-11"></span>
                        <span class="placeholder skeleton-line col-10"></span>
                        <span class="placeholder skeleton-line col-12"></span>
                    </div>
                </div>
                <div class="dashboard-card-header">
                    <h2 class="dashboard-card-title">
                        <i class="bi bi-clock-history text-warning"></i>
                        Transaksi Terbaru
                    </h2>
                </div>

                @if($transaksiTerbaru->isEmpty())
                    <div class="dashboard-empty-state">
                        <div>
                            <span class="dashboard-empty-icon"><i class="bi bi-receipt"></i></span>
                            <h3 class="dashboard-empty-title">Belum ada transaksi.</h3>
                            <p class="dashboard-empty-description">Transaksi terbaru akan tampil setelah kasir menyimpan penjualan.</p>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dashboard table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID Penjualan</th>
                                    <th>Tanggal dan Jam</th>
                                    <th>Nama Kasir</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksiTerbaru as $transaksi)
                                    <tr>
                                        <td class="fw-bold">#{{ $transaksi->id_penjualan }}</td>
                                        <td>{{ $format->dateTime($transaksi->tanggal) }}</td>
                                        <td>{{ $transaksi->user?->nama ?? '-' }}</td>
                                        <td class="text-end fw-bold">{{ $format->money($transaksi->total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="dashboard-card">
        <div class="dashboard-skeleton" aria-hidden="true">
            <div class="placeholder-glow mb-4">
                <span class="placeholder skeleton-line col-4"></span>
            </div>
            <div class="placeholder-glow d-grid gap-2">
                <span class="placeholder skeleton-line col-9"></span>
                <span class="placeholder skeleton-line col-7"></span>
                <span class="placeholder skeleton-line col-8"></span>
            </div>
        </div>
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title">
                <i class="bi bi-info-circle text-primary"></i>
                Ringkasan Operasional Hari Ini
            </h2>
        </div>

        <div class="alert operation-alert">
            @if($transaksiHariIni < 1)
                <div class="dashboard-empty-state">
                    <div>
                        <span class="dashboard-empty-icon"><i class="bi bi-info-circle"></i></span>
                        <h3 class="dashboard-empty-title">Belum ada aktivitas operasional hari ini.</h3>
                        <p class="dashboard-empty-description">Ringkasan akan muncul setelah ada transaksi penjualan pada hari ini.</p>
                    </div>
                </div>
            @else
                <div class="d-grid gap-2">
                    <div>
                        <i class="bi bi-graph-up-arrow text-primary me-1"></i>
                        Pendapatan hari ini mencapai <strong>{{ $format->money($pendapatanHariIni) }}</strong>
                        dari <strong>{{ $format->number($transaksiHariIni) }}</strong> transaksi.
                    </div>

                    @if($menuTeratas)
                        <div>
                            <i class="bi bi-cup-hot text-primary me-1"></i>
                            Menu terlaris hari ini adalah <strong>{{ $menuTeratas->menu?->nama_menu ?? 'Menu tidak ditemukan' }}</strong>
                            dengan <strong>{{ $format->number($menuTeratas->total_terjual) }}</strong> porsi terjual.
                        </div>
                    @endif

                    @if($bahanTeratas)
                        <div>
                            <i class="bi bi-box-seam text-primary me-1"></i>
                            Bahan baku yang paling banyak digunakan hari ini adalah
                            <strong>{{ $bahanTeratas->nama_bahan }}</strong> sebanyak
                            <strong>{{ $format->unit($bahanTeratas->total_pakai, $bahanTeratas->satuan) }}</strong>.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = @json($chartData);
const chartWrap = document.querySelector('.chart-wrap');
const emptyChart = document.getElementById('emptyChart');
const chartButtons = document.querySelectorAll('.js-chart-filter');
const rupiahFormatter = new Intl.NumberFormat('id-ID', {
    maximumFractionDigits: 0
});

window.addEventListener('load', () => {
    window.setTimeout(() => {
        document.documentElement.classList.remove('dashboard-loading');
    }, 180);
});

function getRangeData(range) {
    return chartData[range] || [];
}

function formatRupiah(value) {
    return 'Rp ' + rupiahFormatter.format(Number(value || 0));
}

function setEmptyState(isEmpty) {
    chartWrap.classList.toggle('is-empty', isEmpty);
    emptyChart.style.display = isEmpty ? 'grid' : 'none';
}

const initialData = getRangeData('today');
const salesChart = new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: initialData.map((item) => item.tanggal),
        datasets: [{
            label: 'Pendapatan',
            data: initialData.map((item) => item.total),
            borderWidth: 3.2,
            clip: false,
            fill: true,
            tension: 0.42,
            pointRadius: 7,
            pointHoverRadius: 9,
            pointHitRadius: 14,
            pointBorderWidth: 3,
            pointHoverBorderWidth: 3,
            pointBorderColor: '#ffffff',
            pointBackgroundColor: '#f97316',
            pointHoverBackgroundColor: '#f97316',
            pointHoverBorderColor: '#ffffff',
            backgroundColor: 'rgba(249, 115, 22, 0.16)',
            borderColor: '#f97316',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 900,
            easing: 'easeOutQuart'
        },
        interaction: {
            mode: 'nearest',
            intersect: true
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.94)',
                borderColor: 'rgba(249, 115, 22, 0.35)',
                borderWidth: 1,
                padding: 12,
                displayColors: false,
                titleFont: {
                    size: 13,
                    weight: '700'
                },
                bodyFont: {
                    size: 13,
                    weight: '600'
                },
                callbacks: {
                    title: function(items) {
                        return items[0]?.label || '';
                    },
                    label: function(context) {
                        return 'Total Pendapatan: ' + formatRupiah(context.parsed.y);
                    }
                }
            }
        },
        elements: {
            line: {
                capBezierPoints: true
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                offset: initialData.length === 1
            },
            y: {
                beginAtZero: true,
                grace: '20%',
                ticks: {
                    callback: function(value) {
                        return formatRupiah(value);
                    }
                }
            }
        }
    }
});

setEmptyState(initialData.length === 0);

chartButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const selectedData = getRangeData(button.dataset.range);

        chartButtons.forEach((item) => {
            item.classList.remove('btn-warning', 'text-white', 'active');
            item.classList.add('btn-outline-warning');
        });

        button.classList.remove('btn-outline-warning');
        button.classList.add('btn-warning', 'text-white', 'active');

        salesChart.data.labels = selectedData.map((item) => item.tanggal);
        salesChart.data.datasets[0].data = selectedData.map((item) => item.total);
        salesChart.options.scales.x.offset = selectedData.length === 1;
        salesChart.update();

        setEmptyState(selectedData.length === 0);
    });
});
</script>
@endsection
