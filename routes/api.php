<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\MasterObatController;
use App\Http\Controllers\BatchObatController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('obat')->group(function () {
    Route::get('/', [ObatController::class, 'index']);
    Route::post('/transaksi', [ObatController::class, 'storeTransaksi']);
    Route::get('/{id}/perpetual', [ObatController::class, 'laporanPerpetual']);
    Route::post('/tutup-bulan', [ObatController::class, 'tutupBulan']);
});

Route::prefix('master-obat')->group(function () {
    Route::get('/', [MasterObatController::class, 'index']);
    Route::post('/', [MasterObatController::class, 'store']);
    Route::put('/{id}', [MasterObatController::class, 'update']);
    Route::delete('/{id}', [MasterObatController::class, 'destroy']);
});

Route::prefix('batch-obat')->group(function () {
    Route::get('/{id_obat}', [BatchObatController::class, 'index']);
    Route::post('/', [BatchObatController::class, 'store']);
    Route::put('/{id}', [BatchObatController::class, 'update']);
    Route::delete('/{id}', [BatchObatController::class, 'destroy']);
});
