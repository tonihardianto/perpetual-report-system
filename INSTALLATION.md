# Panduan Instalasi - Perpetual Report System

Sistem Laporan Perpetual berbasis Laravel untuk manajemen stok obat dengan fitur batch tracking, FEFO (First Expired First Out), dan stock opname.

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL >= 5.7 atau MariaDB >= 10.3
- Node.js >= 16.x & NPM
- Apache/Nginx Web Server

## Langkah-Langkah Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/tonihardianto/perpetual-report-system.git
cd perpetual-report-system
```

### 2. Install Dependencies

**Install PHP Dependencies:**
```bash
composer install
```

**Install Node Dependencies:**
```bash
npm install
```

### 3. Konfigurasi Environment

**Buat file `.env` dari template:**
```bash
cp .env.example .env
```

**Generate Application Key:**
```bash
php artisan key:generate
```

**Edit file `.env` dan sesuaikan konfigurasi database:**
```env
APP_NAME="Perpetual Report System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpetual_report
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Setup Database

**Buat database baru:**
```sql
CREATE DATABASE perpetual_report CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Jalankan migrasi database:**
```bash
php artisan migrate
```

**Jalankan seeder (opsional - untuk data dummy):**
```bash
php artisan db:seed
```

### 5. Setup Storage

**Buat symbolic link untuk storage:**
```bash
php artisan storage:link
```

### 6. Build Assets

**Development mode:**
```bash
npm run dev
```

**Production mode:**
```bash
npm run build
```

### 7. Jalankan Aplikasi

**Menggunakan Laravel development server:**
```bash
php artisan serve
```

Aplikasi akan berjalan di: `http://localhost:8000`

**Atau menggunakan Laravel Valet (Mac) / Laragon (Windows):**
```bash
# Untuk Valet
valet link perpetual-report

# Akses via: http://perpetual-report.test
```

## Konfigurasi Tambahan

### Setup Permission (Spatie Laravel Permission)

Sistem ini menggunakan Spatie Laravel Permission untuk manajemen role dan permission.

```bash
php artisan permission:cache-reset
```

### Setup Excel Export

Sistem menggunakan Maatwebsite Excel untuk export laporan. Pastikan ekstensi PHP berikut sudah diaktifkan:
- php_zip
- php_xml
- php_gd2

## Struktur Folder Penting

```
perpetual-report-system/
├── app/
│   ├── Models/              # Model database
│   ├── Services/            # Business logic layer
│   │   ├── InventoryService.php
│   │   ├── ReportService.php
│   │   └── StockService.php
│   ├── Exports/             # Export Excel
│   └── Imports/             # Import Excel
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   └── js/                  # JavaScript files
└── routes/
    └── web.php              # Web routes
```

## User Default (Setelah Seeder)

Jika menjalankan seeder, user default yang dibuat:

- **Email:** admin@example.com
- **Password:** password

**PENTING:** Segera ubah password default setelah login pertama kali!

## Troubleshooting

### Permission Denied Error

```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

### Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Regenerate Autoload

```bash
composer dump-autoload
```

### Error "No application encryption key"

```bash
php artisan key:generate
```

## Fitur Utama

- ✅ **Manajemen Master Data Obat** - CRUD obat dengan tracking batch
- ✅ **Transaksi Mutasi Stok** - Pembelian, pemakaian, dan penyesuaian
- ✅ **Stock Opname** - Proses SO dengan 3 fase (Pembuka, Sisa Fisik, Penutup)
- ✅ **Laporan Perpetual Tahunan** - Kartu stok per batch dengan FEFO
- ✅ **Export ke Excel** - Download laporan dalam format .xlsx
- ✅ **Filter Multiple Obat** - Filter laporan untuk beberapa obat sekaligus
- ✅ **Custom Rolling Batch** - Logika FEFO untuk saldo awal

## Update Aplikasi

```bash
# Pull perubahan terbaru
git pull origin main

# Update dependencies
composer install
npm install

# Jalankan migrasi baru (jika ada)
php artisan migrate

# Build assets
npm run build

# Clear cache
php artisan optimize:clear
```

## Support & Dokumentasi

Untuk pertanyaan atau issue, silakan buat issue di GitHub repository:
https://github.com/tonihardianto/perpetual-report-system/issues

---

**Developed by:** Toni Hardianto  
**Laravel Version:** 11.x  
**Last Updated:** December 2025
