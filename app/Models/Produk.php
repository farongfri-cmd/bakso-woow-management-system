<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'nama_menu',
        'nama_produk',
        'harga',
        'status',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function getIdProdukAttribute()
    {
        return $this->id_menu;
    }

    public function getNamaProdukAttribute()
    {
        return $this->nama_menu;
    }

    public function setNamaProdukAttribute($value): void
    {
        $this->attributes['nama_menu'] = $value;
    }
}
