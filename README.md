# QASSIR IN

Aplikasi kasir berbasis web untuk mengelola menu, transaksi, dan pesanan mandiri melalui QR code. Proyek ini menggunakan Laravel 12 dan disiapkan untuk kebutuhan operasional kedai.

## Fitur

- Dashboard ringkasan pendapatan, grafik penjualan, produk terlaris, dan pesanan yang menunggu diproses.
- Pengelolaan menu, termasuk tambah, ubah, hapus, dan pencarian.
- Transaksi kasir dengan perhitungan total, pembayaran, dan kembalian.
- Riwayat transaksi, pembatalan transaksi pada hari yang sama, serta cetak struk.
- Self order pelanggan dan pemantauan pesanan oleh admin.
- Halaman QR code untuk akses self order.

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- SQLite (konfigurasi awal)
- Node.js dan npm untuk aset frontend
- Vite, Tailwind CSS, dan Simple QrCode

## Persiapan lokal

Pastikan PHP, Composer, Node.js, dan npm sudah terpasang.

```bash
git clone https://github.com/anggisaputra-23/QASSIR_IN-1-.git
cd QASSIR_IN-1-
composer install
npm install
```

Buat file konfigurasi lokal dan database SQLite. Jangan unggah `.env` atau database lokal ke GitHub.

```bash
cp .env.example .env
```

Di Windows PowerShell, gunakan `Copy-Item .env.example .env` sebagai pengganti `cp`.

Buat file database bila belum ada:

```bash
# Linux / macOS
touch database/database.sqlite
```

```powershell
# Windows PowerShell
New-Item database/database.sqlite -ItemType File -Force
```

Pastikan `.env` memakai `DB_CONNECTION=sqlite`, lalu jalankan:

```bash
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Buka alamat lokal yang ditampilkan oleh `php artisan serve`. Seeder akan mengisi data contoh menu dan satu akun contoh. Sesuaikan data tersebut sebelum dipakai.

## Keamanan dan konfigurasi

- Simpan kredensial hanya di `.env`; `.env` dan file database SQLite lokal diabaikan oleh Git.
- Untuk produksi, gunakan `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, dan `APP_KEY` yang unik. Jangan gunakan konfigurasi lokal atau data seeder sebagai konfigurasi produksi.
- **Catatan sebelum deployment publik:** route dashboard, menu, transaksi, dan administrasi saat ini belum dilindungi middleware autentikasi/otorisasi. Tambahkan kontrol akses dan uji alur login sebelum aplikasi dibuka ke internet.

## Pengujian

Jalankan test suite Laravel dengan:

```bash
php artisan test
```

## Versi

Versi awal proyek: **1.0.0**.