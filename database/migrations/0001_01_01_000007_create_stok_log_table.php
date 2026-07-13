<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_log', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('id_bahan')
                ->constrained('bahan_baku', 'id_bahan')
                ->onDelete('cascade');
            $table->decimal('perubahan_stok', 12, 2); // bisa positif (koreksi tambah) atau negatif (terjual/berkurang)
            $table->dateTime('tanggal_log');
            $table->string('keterangan')->nullable(); // contoh: "Penjualan #12", "Koreksi manual - rusak"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_log');
    }
};
