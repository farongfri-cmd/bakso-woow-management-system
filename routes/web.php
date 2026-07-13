<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ResepController;

// ===== HALAMAN PUBLIK (tanpa login) =====
Route::get('/', function () {
    return view('home');
});

// ===== AUTH =====
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== WAJIB LOGIN (admin & kasir) =====
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::get('/transaksi/{id}/detail', [TransaksiController::class, 'detail']);
    Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);
    Route::get('/transaksi/struk/{id}', [TransaksiController::class, 'struk']);
});

// ===== KHUSUS ADMIN =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('produk', ProdukController::class);
    Route::patch('/produk/{produk}/status', [ProdukController::class, 'toggleStatus']);
    Route::resource('bahan-baku', BahanBakuController::class)
        ->except(['show'])
        ->parameters([
            'bahan-baku' => 'bahan_baku',
        ]);
    Route::get('/bahan-baku/{bahan_baku}/penyesuaian-stok', [BahanBakuController::class, 'penyesuaianStok']);
    Route::post('/bahan-baku/{bahan_baku}/penyesuaian-stok', [BahanBakuController::class, 'simpanPenyesuaianStok']);
    Route::patch('/bahan-baku/{bahan_baku}/status', [BahanBakuController::class, 'toggleStatus']);
    Route::resource('resep', ResepController::class)->except(['show']);

    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf']);
    Route::get('/laporan/excel', [LaporanController::class, 'excel']);
});
