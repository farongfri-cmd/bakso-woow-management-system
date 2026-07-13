<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';
    protected $primaryKey = 'id_bahan';

    protected $fillable = [
        'nama_bahan',
        'satuan',
        'stok',
        'status',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Relasi: satu bahan baku bisa dipakai di banyak detail resep
    public function detailResep(): HasMany
    {
        return $this->hasMany(DetailResep::class, 'id_bahan', 'id_bahan');
    }

    // Relasi: satu bahan baku punya banyak riwayat perubahan stok
    public function stokLog(): HasMany
    {
        return $this->hasMany(StokLog::class, 'id_bahan', 'id_bahan');
    }

    /**
     * Kurangi stok bahan baku dan catat ke stok_log.
     * Dipakai saat transaksi penjualan terjadi.
     */
    public function kurangiStok(float $jumlah, string $keterangan): void
    {
        $this->decrement('stok', $jumlah);

        $this->stokLog()->create([
            'perubahan_stok' => -$jumlah,
            'tanggal_log' => now(),
            'keterangan' => $keterangan,
        ]);
    }

    /**
     * Koreksi stok manual (bisa nambah atau ngurangi).
     */
    public function koreksiStok(float $perubahan, string $keterangan): void
    {
        $this->increment('stok', $perubahan);

        $this->stokLog()->create([
            'perubahan_stok' => $perubahan,
            'tanggal_log' => now(),
            'keterangan' => $keterangan,
        ]);
    }
}
