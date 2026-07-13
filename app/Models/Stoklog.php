<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokLog extends Model
{
    use HasFactory;

    protected $table = 'stok_log';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_bahan',
        'perubahan_stok',
        'tanggal_log',
        'keterangan',
    ];

    // Relasi: log stok merujuk ke satu bahan baku
    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan', 'id_bahan');
    }
}