# Perpetual Report System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistem Laporan Perpetual berbasis Laravel untuk manajemen stok obat/farmasi dengan fitur batch tracking, FEFO (First Expired First Out), dan stock opname yang komprehensif.

## 🚀 Fitur Utama

- **📦 Manajemen Master Data Obat**
  - CRUD obat dengan tracking batch
  - Pencatatan HPP per satuan
  - Multi-batch per obat
  - Tracking tanggal expired (ED)

- **📊 Transaksi Mutasi Stok**
  - Transaksi Pembelian (MASUK)
  - Transaksi Pemakaian (KELUAR)
  - Transaksi Penyesuaian
  - History lengkap semua transaksi

- **🔍 Stock Opname**
  - Proses SO dengan 3 fase otomatis:
    1. Jurnal Pembuka (memindahkan stok ke buffer)
    2. Pencatatan Sisa Fisik (hasil SO)
    3. Jurnal Penutup (selisih/loss)
  - Validasi data SO
  - Tracking per periode

- **📈 Laporan Perpetual Tahunan**
  - Kartu stok per batch dengan FEFO
  - Tampilan horizontal 12 bulan
  - Saldo awal otomatis
  - Mutasi bulanan (Masuk/Keluar/Sisa)
  - Filter multiple obat
  - Filter per tahun

- **📥 Export ke Excel**
  - Export laporan lengkap
  - Format xlsx
  - Sesuai dengan filter yang dipilih

- **🔐 Manajemen User & Permission**
  - Role-based access control
  - Spatie Laravel Permission

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL >= 5.7 / MariaDB >= 10.3
- Node.js >= 16.x & NPM
- Web Server (Apache/Nginx)

## 🛠️ Instalasi

Untuk panduan instalasi lengkap, silakan baca [INSTALLATION.md](INSTALLATION.md)

### Quick Start

```bash
# Clone repository
git clone https://github.com/tonihardianto/perpetual-report-system.git
cd perpetual-report-system

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database (sesuaikan .env)
php artisan migrate

# Build assets
npm run build

# Jalankan aplikasi
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 📖 Dokumentasi

- [Panduan Instalasi](INSTALLATION.md)
- [Struktur Database](#struktur-database)
- [API Documentation](#api-documentation)

## 🗄️ Struktur Database

### Tabel Utama

- `obat` - Master data obat
- `batch_obat` - Tracking batch obat dengan ED
- `transaksi_mutasi` - Semua transaksi stok
- `stock_opname_header` - Header SO per periode
- `stock_opname` - Detail SO per batch

### Model Relationships

```
Obat
  └── hasMany BatchObat
        └── hasMany TransaksiMutasi

StockOpnameHeader
  └── hasMany StockOpname
        └── belongsTo BatchObat
```

## 🔧 Services

Aplikasi menggunakan Service Layer Pattern:

- **InventoryService** - Manajemen stok dan batch
- **ReportService** - Generate laporan perpetual
- **StockService** - Operasi stock opname

## 🎯 Business Logic

### FEFO (First Expired First Out)

Sistem menggunakan logika FEFO untuk menentukan batch mana yang digunakan terlebih dahulu:
- Batch dengan tanggal ED paling dekat akan digunakan pertama
- Custom Rolling Batch untuk saldo awal tahunan
- Tracking mutasi per batch

### Stock Opname (3 Fase)

1. **Fase 1 - Jurnal Pembuka**
   - Memindahkan saldo sistem ke buffer
   - Transaksi MASUK dengan referensi `OP-SO-{id}`

2. **Fase 2 - Sisa Fisik**
   - Input hasil hitung fisik
   - Transaksi PENYESUAIAN (positif)
   - Keterangan: "Sisa Fisik untuk Reporting"

3. **Fase 3 - Jurnal Penutup**
   - Menghitung selisih
   - Transaksi PENYESUAIAN (negatif) atau KELUAR
   - Menutup buffer

## 🖥️ Tech Stack

- **Backend:** Laravel 11.x
- **Frontend:** Blade Templates, Bootstrap 5
- **Database:** MySQL/MariaDB
- **Excel Export:** Maatwebsite/Laravel-Excel
- **Permission:** Spatie/Laravel-Permission
- **Build Tools:** Vite

## 📊 Laporan Perpetual

Format laporan:

```
| Obat | Batch | ED | HPP | Saldo Awal | Jan | Feb | ... | Dec |
|------|-------|----|----|------------|-----|-----|-----|-----|
|      |       |    |    | Qty | Value | M/K/S | M/K/S | ... |
```

Keterangan:
- **M** = Masuk (Pembelian)
- **K** = Keluar (Pemakaian/Loss)
- **S** = Sisa Stok (Saldo Akhir)

## 🤝 Contributing

Kontribusi selalu diterima! Silakan:

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 License

Project ini menggunakan lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail.

## 👨‍💻 Author

**Toni Hardianto**

- GitHub: [@tonihardianto](https://github.com/tonihardianto)
- Repository: [perpetual-report-system](https://github.com/tonihardianto/perpetual-report-system)

## 🐛 Issue & Support

Jika menemukan bug atau ingin request fitur, silakan buat [issue baru](https://github.com/tonihardianto/perpetual-report-system/issues).

## 📅 Changelog

### Version 1.0.0 (December 2025)
- ✅ Initial release
- ✅ Manajemen master obat & batch
- ✅ Transaksi mutasi stok
- ✅ Stock opname 3 fase
- ✅ Laporan perpetual tahunan
- ✅ Export Excel
- ✅ Filter multiple obat
- ✅ Auto-update tahun filter

---

**Made with ❤️ using Laravel**