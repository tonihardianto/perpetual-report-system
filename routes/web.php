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
    Route::resource('master-obat', MasterObatController::class);
    Route::post('master-obat/import', [MasterObatController::class, 'import'])->name('master-obat.import');
    // Route untuk pencarian obat via Select2 AJAX
    Route::get('select2/obat', [MasterObatController::class, 'select2Search'])->name('select2.obat.search');

    Route::get('transaksi-index', [TransaksiMutasiController::class, 'index'])->name('transaksi.mutasi.index'); 
    Route::get('transaksi-masuk', [TransaksiMasukController::class, 'create'])->name('transaksi.masuk.create');
    Route::post('transaksi-masuk', [TransaksiMasukController::class, 'store'])->name('transaksi.masuk.store');
    Route::get('transaksi-keluar', [TransaksiKeluarController::class, 'create'])->name('transaksi.keluar.create');
    Route::post('transaksi-keluar', [TransaksiKeluarController::class, 'store'])->name('transaksi.keluar.store');
    Route::get('laporan-perpetual', [PerpetualReportController::class, 'index'])->name('laporan.perpetual.index');
    Route::get('laporan-perpetual/export', [PerpetualReportController::class, 'export'])->name('laporan.perpetual.export');
    
    // Stock Opname (SO)
    Route::get('stock-opname', [StockOpnameController::class, 'index'])->name('transaksi.stock-opname.index');
    Route::post('stock-opname/process', [StockOpnameController::class, 'process'])->name('transaksi.stock-opname.process');

    Route::post('tutup-buku/rolling-batch', [CloseBookController::class, 'runRollingBatch'])->name('tutup.buku.run')->middleware('auth'); // Pastikan hanya user terotorisasi yang bisa akses
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
    
    // // Stock Opname
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
    
});



// =====================================================
// ⚠️ WILDCARD — letakkan PALING BAWAH
// =====================================================
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
