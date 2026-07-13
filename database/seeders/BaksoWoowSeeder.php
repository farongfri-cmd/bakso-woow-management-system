<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use App\Models\Menu;
use App\Models\Resep;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BaksoWoowSeeder extends Seeder
{
    public function run(): void
    {
        // ===========================================
        // 1. USER (Admin & Kasir)
        // ===========================================
        $admin = User::create([
            'nama' => 'Admin Bakso Woow',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $kasir = User::create([
            'nama' => 'Kasir Bakso Woow',
            'username' => 'kasir',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);

        // ===========================================
        // 2. BAHAN BAKU (stok awal)
        // ===========================================
        $bahan = [
            'bakso_besar_pedas' => BahanBaku::create(['nama_bahan' => 'Bakso Besar Pedas', 'satuan' => 'pcs', 'stok' => 100]),
            'bakso_besar_urat'  => BahanBaku::create(['nama_bahan' => 'Bakso Besar Urat', 'satuan' => 'pcs', 'stok' => 100]),
            'bakso_besar_telor' => BahanBaku::create(['nama_bahan' => 'Bakso Besar Telor', 'satuan' => 'pcs', 'stok' => 100]),
            'bakso_besar_keju'  => BahanBaku::create(['nama_bahan' => 'Bakso Besar Keju', 'satuan' => 'pcs', 'stok' => 100]),
            'bakso_kecil'       => BahanBaku::create(['nama_bahan' => 'Bakso Kecil', 'satuan' => 'pcs', 'stok' => 300]),
            'mie'               => BahanBaku::create(['nama_bahan' => 'Mie', 'satuan' => 'gram', 'stok' => 10000]),
            'ayam'              => BahanBaku::create(['nama_bahan' => 'Ayam', 'satuan' => 'gram', 'stok' => 2000]),
            'pangsit'           => BahanBaku::create(['nama_bahan' => 'Pangsit', 'satuan' => 'pcs', 'stok' => 200]),
            'tahu_bakso'        => BahanBaku::create(['nama_bahan' => 'Tahu Bakso', 'satuan' => 'pcs', 'stok' => 100]),
            'nasi'              => BahanBaku::create(['nama_bahan' => 'Nasi', 'satuan' => 'pcs', 'stok' => 100]),
            'indomie'           => BahanBaku::create(['nama_bahan' => 'Indomie', 'satuan' => 'pcs', 'stok' => 100]),
        ];

        // ===========================================
        // 3. MENU + RESEP
        // Format: [nama_menu, harga, [ [bahan_key, jumlah_pakai], ... ] ]
        // ===========================================
        $daftarMenu = [
            ['Bakso Woow (Pedas)', 20000, [
                ['bakso_besar_pedas', 1], ['bakso_kecil', 2],
            ]],
            ['Bakso Urat', 20000, [
                ['bakso_besar_urat', 1], ['bakso_kecil', 2],
            ]],
            ['Bakso Telor', 20000, [
                ['bakso_besar_telor', 1], ['bakso_kecil', 2],
            ]],
            ['Bakso Keju', 20000, [
                ['bakso_besar_keju', 1], ['bakso_kecil', 2],
            ]],
            ['Bakso Kecil-kecil', 15000, [
                ['bakso_kecil', 5],
            ]],
            ['Bakso Combo Woow + Woow', 33000, [
                ['bakso_besar_pedas', 2], ['bakso_kecil', 2],
            ]],
            ['Bakso Combo Woow + Urat', 33000, [
                ['bakso_besar_pedas', 1], ['bakso_besar_urat', 1], ['bakso_kecil', 2],
            ]],
            ['Bakso Combo Woow + Telor', 33000, [
                ['bakso_besar_pedas', 1], ['bakso_besar_telor', 1], ['bakso_kecil', 2],
            ]],
            ['Bakso Combo Urat + Urat', 33000, [
                ['bakso_besar_urat', 2], ['bakso_kecil', 2],
            ]],
            ['Bakso Combo Urat + Telor', 33000, [
                ['bakso_besar_urat', 1], ['bakso_besar_telor', 1], ['bakso_kecil', 2],
            ]],
            ['Bakso Combo Telor + Telor', 33000, [
                ['bakso_besar_telor', 2], ['bakso_kecil', 2],
            ]],
            ['Bakso Kecil-kecil 1/2 porsi', 10000, [
                ['bakso_kecil', 3],
            ]],
            ['Bakso Woow (Pedas) 1 pcs', 15000, [
                ['bakso_besar_pedas', 1],
            ]],
            ['Bakso Urat 1 pcs', 15000, [
                ['bakso_besar_urat', 1],
            ]],
            ['Bakso Telor 1 pcs', 15000, [
                ['bakso_besar_telor', 1],
            ]],
            ['Tahu Bakso', 3000, [
                ['tahu_bakso', 1],
            ]],
            ['Nasi', 5000, [
                ['nasi', 1],
            ]],
            ['Mie Ayam Biasa', 13000, [
                ['mie', 83], ['ayam', 5],
            ]],
            ['Mie Ayam Bakso Woow (Pedas)', 27000, [
                ['mie', 83], ['ayam', 5], ['bakso_besar_pedas', 1],
            ]],
            ['Mie Ayam Bakso Urat', 27000, [
                ['mie', 83], ['ayam', 5], ['bakso_besar_urat', 1],
            ]],
            ['Mie Ayam Bakso Telor', 27000, [
                ['mie', 83], ['ayam', 5], ['bakso_besar_telor', 1],
            ]],
            ['Mie Ayam Bakso Kecil-kecil', 18000, [
                ['mie', 83], ['ayam', 5], ['bakso_kecil', 2],
            ]],
            ['Mie Ayam Bakso Pangsit Rebus', 16000, [
                ['mie', 83], ['ayam', 5], ['pangsit', 2],
            ]],
            ['Pangsit Rebus', 10000, [
                ['pangsit', 5],
            ]],
            ['Bakso Woow (Pedas) + Indomie', 25000, [
                ['bakso_besar_pedas', 1], ['bakso_kecil', 2], ['indomie', 1],
            ]],
            ['Bakso Urat + Indomie', 25000, [
                ['bakso_besar_urat', 1], ['bakso_kecil', 2], ['indomie', 1],
            ]],
            ['Bakso Telor + Indomie', 25000, [
                ['bakso_besar_telor', 1], ['bakso_kecil', 2], ['indomie', 1],
            ]],
            ['Bakso Kecil-kecil + Indomie', 20000, [
                ['bakso_kecil', 5], ['indomie', 1],
            ]],
        ];

        foreach ($daftarMenu as [$namaMenu, $harga, $komposisi]) {
            $menu = Menu::create([
                'nama_menu' => $namaMenu,
                'harga' => $harga,
            ]);

            $resep = Resep::create([
                'id_menu' => $menu->id_menu,
            ]);

            foreach ($komposisi as [$bahanKey, $jumlahPakai]) {
                $resep->detailResep()->create([
                    'id_bahan' => $bahan[$bahanKey]->id_bahan,
                    'jumlah_pakai' => $jumlahPakai,
                ]);
            }
        }

        $this->command->info('Seeder Bakso Woow berhasil: ' . count($bahan) . ' bahan baku, ' . count($daftarMenu) . ' menu + resep, 2 user.');
    }
}