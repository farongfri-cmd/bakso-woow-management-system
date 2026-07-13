<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_resep', function (Blueprint $table) {
            $table->id('id_detail_resep');
            $table->foreignId('id_resep')
                ->constrained('resep', 'id_resep')
                ->onDelete('cascade');
            $table->foreignId('id_bahan')
                ->constrained('bahan_baku', 'id_bahan')
                ->onDelete('cascade');
            $table->decimal('jumlah_pakai', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_resep');
    }
};
