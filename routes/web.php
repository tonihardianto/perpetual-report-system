<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MasterObatController;
use App\Http\Controllers\BatchObatController;
use App\Http\Controllers\TransaksiObatController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\LaporanPerpetualController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\PerpetualReportController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;
use App\Http\Controllers\TransaksiMutasiController;
use App\Http\Controllers\CloseBookController;


Auth::routes();

// Example: protect admin-only routes using spatie middleware (role or permission)
// After installing spatie/laravel-permission and publishing, you can use:
// Route::middleware(['auth','role:super-admin|admin'])->group(function () {
//     Route::get('/admin-dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
// });

// Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// Root
Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::middleware('auth')->group(function () {
    // Update Profile
    Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');


    // =====================================================
    // 🧱 Master Obat
    // =====================================================
    Route::middleware(['permission:manage obat'])->group(function () {
        Route::resource('master-obat', MasterObatController::class);
        Route::post('master-obat/import', [MasterObatController::class, 'import'])->name('master-obat.import');
    });
    // Route untuk pencarian obat via Select2 AJAX (accessible by all authenticated users)
    Route::get('select2/obat', [MasterObatController::class, 'select2Search'])->name('select2.obat.search');

    Route::middleware(['permission:process mutasi'])->group(function () {
        Route::get('transaksi-index', [TransaksiMutasiController::class, 'index'])->name('transaksi.mutasi.index');
        Route::get('transaksi-masuk', [TransaksiMasukController::class, 'create'])->name('transaksi.masuk.create');
        Route::post('transaksi-masuk', [TransaksiMasukController::class, 'store'])->name('transaksi.masuk.store');
        Route::get('transaksi-keluar', [TransaksiKeluarController::class, 'create'])->name('transaksi.keluar.create');
        Route::post('transaksi-keluar', [TransaksiKeluarController::class, 'store'])->name('transaksi.keluar.store');
    });
    Route::middleware(['permission:view reports'])->group(function () {
        Route::get('laporan-perpetual', [PerpetualReportController::class, 'index'])->name('laporan.perpetual.index');
    });

    Route::middleware(['permission:export reports'])->group(function () {
        Route::get('laporan-perpetual/export', [PerpetualReportController::class, 'export'])->name('laporan.perpetual.export');
    });

    // Input Sisa Stock (SO)
    Route::middleware(['permission:perform stock-opname'])->group(function () {
        Route::get('stock-opname', [StockOpnameController::class, 'index'])->name('transaksi.stock-opname.index'); // Riwayat
        Route::get('stock-opname/create', [StockOpnameController::class, 'create'])->name('transaksi.stock-opname.create'); // Pilih Periode
        Route::get('stock-opname/form', [StockOpnameController::class, 'showForm'])->name('transaksi.stock-opname.showForm'); // Form SO Parsial
        Route::get('stock-opname/search-obat', [StockOpnameController::class, 'searchObatForSo'])->name('transaksi.stock-opname.searchObat');
        Route::post('stock-opname/process', [StockOpnameController::class, 'process'])->name('transaksi.stock-opname.process'); // Proses SO
        Route::get('stock-opname/{stockOpnameHeader}', [StockOpnameController::class, 'show'])->name('transaksi.stock-opname.show'); // Lihat Detail Riwayat
        Route::post('stock-opname/close-month', [StockOpnameController::class, 'closeMonth'])->name('transaksi.stock-opname.closeMonth'); // Tutup Bulan
    });

    Route::middleware(['permission:manage settings'])->group(function () {
        Route::post('tutup-buku/rolling-batch', [CloseBookController::class, 'runRollingBatch'])->name('tutup.buku.run');
    });
    // =====================================================
    // 💊 Batch Obat
    // =====================================================
    // Route::get('/batch-obat/{id_obat}', [BatchObatController::class, 'indexView'])->name('batch-obat.index');
    // Route::post('/batch-obat', [BatchObatController::class, 'storeView'])->name('batch-obat.store');
    // Route::put('/batch-obat/{id}', [BatchObatController::class, 'updateView'])->name('batch-obat.update');
    // Route::delete('/batch-obat/{id}', [BatchObatController::class, 'destroyView'])->name('batch-obat.destroy');


    // // Transaksi Obat
    // Route::prefix('transaksi-obat')->group(function () {
    //     Route::get('/', [TransaksiObatController::class, 'index'])->name('transaksi-obat.index');
    //     Route::get('/create', [TransaksiObatController::class, 'create'])->name('transaksi-obat.create');
    //     Route::post('/', [TransaksiObatController::class, 'store'])->name('transaksi-obat.store');
    //     Route::get('/get-batch/{id_obat}', [TransaksiObatController::class, 'getBatchByObat'])->name('transaksi-obat.get-batch');

    // });

    // // Input Sisa Stock
    // Route::prefix('stock-opname')->group(function () {
    //     Route::get('/', [StockOpnameController::class, 'index'])->name('stock-opname.index');
    //     Route::post('/', [StockOpnameController::class, 'store'])->name('stock-opname.store');
    //     Route::post('/tutup-bulan', [StockOpnameController::class, 'tutupBulan'])->name('stock-opname.tutup-bulan');
    // });

    // // Laporan Stok
    // Route::prefix('laporan-stok')->group(function () {
    //     Route::get('/', [LaporanStokController::class, 'index'])->name('laporan-stok.index');
    // });

    // // Laporan Perpetual
    // Route::prefix('laporan-perpetual')->group(function () {
    //     Route::get('/', [LaporanPerpetualController::class, 'index'])->name('laporan-perpetual.index');
    //     Route::get('/export', [LaporanPerpetualController::class, 'export'])->name('laporan-perpetual.export');
    // });

    // Route::get('/stok-opname', [StokOpnameController::class, 'index'])->name('stok-opname.index');

    // Admin — User management (assign roles)
    Route::middleware(['role:super-admin'])->group(function () {
        Route::get('admin/users', [App\Http\Controllers\UserManagementController::class, 'index'])->name('admin.users.index');
        Route::get('admin/users/create', [App\Http\Controllers\UserManagementController::class, 'create'])->name('admin.users.create');
        Route::post('admin/users', [App\Http\Controllers\UserManagementController::class, 'store'])->name('admin.users.store');
        Route::get('admin/users/{user}/edit', [App\Http\Controllers\UserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('admin/users/{user}', [App\Http\Controllers\UserManagementController::class, 'update'])->name('admin.users.update');
        Route::delete('admin/users/{user}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('admin.users.destroy');

        // Role management (assign permissions)
        Route::get('admin/roles', [App\Http\Controllers\RoleManagementController::class, 'index'])->name('admin.roles.index');
        Route::get('admin/roles/create', [App\Http\Controllers\RoleManagementController::class, 'create'])->name('admin.roles.create');
        Route::post('admin/roles', [App\Http\Controllers\RoleManagementController::class, 'store'])->name('admin.roles.store');
        Route::get('admin/roles/{role}/edit', [App\Http\Controllers\RoleManagementController::class, 'edit'])->name('admin.roles.edit');
        Route::put('admin/roles/{role}', [App\Http\Controllers\RoleManagementController::class, 'update'])->name('admin.roles.update');
        Route::delete('admin/roles/{role}', [App\Http\Controllers\RoleManagementController::class, 'destroy'])->name('admin.roles.destroy');
    });
});



// =====================================================
// ⚠️ WILDCARD — letakkan PALING BAWAH
// =====================================================
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
