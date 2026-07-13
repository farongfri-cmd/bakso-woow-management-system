@extends('layouts.app')

@section('content')

<div class="card p-4 shadow mt-4 text-center">
    <h3 class="mb-2">Selamat Datang di Sistem Penjualan</h3>
    
    <p class="mb-4">
        Sistem ini membantu mengelola produk, transaksi,
        dan laporan penjualan secara efisien dan terintegrasi.
    </p>

    <div class="d-flex justify-content-center gap-2 flex-wrap">
        <a href="/produk" class="btn btn-primary">Kelola Produk</a>
        <a href="/transaksi" class="btn btn-success">Transaksi</a>
        <a href="/laporan" class="btn btn-warning">Laporan</a>
        <a href="/dashboard" class="btn btn-dark">Dashboard</a>
    </div>
</div>

@endsection