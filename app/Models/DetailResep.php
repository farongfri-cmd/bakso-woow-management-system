<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailResep extends Model
{
    use HasFactory;

    protected $table = 'detail_resep';
    protected $primaryKey = 'id_detail_resep';

    protected $fillable = [
        'id_resep',
        'id_bahan',
        'jumlah_pakai',
    ];

    // Relasi: detail resep milik satu resep
    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class, 'id_resep', 'id_resep');
    }

    // Relasi: detail resep merujuk ke satu bahan baku
    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan', 'id_bahan');
    }
}