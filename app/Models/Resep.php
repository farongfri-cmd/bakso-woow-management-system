<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resep extends Model
{
    use HasFactory;

    protected $table = 'resep';
    protected $primaryKey = 'id_resep';

    protected $fillable = [
        'id_menu',
    ];

    // Relasi: satu resep milik satu menu
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    // Relasi: satu resep punya banyak detail (komposisi bahan baku)
    public function detailResep(): HasMany
    {
        return $this->hasMany(DetailResep::class, 'id_resep', 'id_resep');
    }
}