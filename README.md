# 🍜 Bakso Woow Management System

Sistem Informasi Manajemen Penjualan dan Stok Bahan Baku Berbasis Web menggunakan Laravel dengan penerapan Service-Oriented Architecture (SOA).

---

## 📖 Tentang Proyek

Aplikasi ini dikembangkan sebagai tugas akhir (Skripsi) Program Studi Sistem Informasi.

Tujuan utama sistem adalah membantu proses:

- Pengelolaan Data Menu
- Pengelolaan Bahan Baku
- Pengelolaan Resep
- Transaksi Penjualan
- Pengurangan Stok Otomatis
- Laporan Penjualan
- Monitoring Persediaan

---

## ✨ Fitur

- Login Admin
- Dashboard
- Manajemen Menu
- Manajemen Bahan Baku
- Manajemen Resep
- Transaksi Penjualan
- Pengurangan Stok Otomatis
- Laporan Penjualan
- Export PDF
- Export Excel

---

## 🏗 Arsitektur Sistem

Aplikasi menerapkan konsep Service-Oriented Architecture (SOA) menggunakan Service Layer.

Service yang digunakan:

- AuthenticationService
- DashboardService
- InventoryService
- ProductService
- RecipeService
- SalesService
- ReportService

---

## 💻 Teknologi

- Laravel
- PHP
- MySQL
- Bootstrap 5
- JavaScript
- Service-Oriented Architecture (SOA)

---

## 📂 Struktur Database

- users
- menu
- bahan_baku
- resep
- detail_resep
- penjualan
- detail_penjualan
- stok_log

---

## 📸 Tampilan Sistem

### Dashboard

*(Tambahkan screenshot)*

### Menu

*(Tambahkan screenshot)*

### Bahan Baku

*(Tambahkan screenshot)*

### Resep

*(Tambahkan screenshot)*

### Transaksi

*(Tambahkan screenshot)*

### Laporan

*(Tambahkan screenshot)*

---

## 🚀 Cara Menjalankan

```bash
git clone https://github.com/farongfri-cmd/bakso-woow-management-system.git

cd bakso-woow-management-system

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan serve
```

---

## 👨‍🎓 Penulis

**Faron**

Program Studi Sistem Informasi

Universitas ...

---

## 📄 Lisensi

Repository ini dibuat untuk keperluan akademik (Tugas Akhir/Skripsi).
