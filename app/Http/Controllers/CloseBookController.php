<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CloseBookController extends Controller
{
    public function runRollingBatch(Request $request)
    {
        // 1. Validasi Keamanan (Opsional: Hanya user tertentu yang boleh)
        // if (!auth()->user()->can('run-close-book')) { ... }

        try {
            // 2. Eksekusi Artisan Command
            $resultCode = Artisan::call('inventory:reset-batch-stok');
            
            // 3. Ambil Output (Opsional, untuk logging detail)
            $output = Artisan::output();

            if ($resultCode === 0) { // Command berhasil (kode 0)
                Log::info('Tutup Buku/Rolling Batch berhasil dieksekusi oleh user ' . auth()->id());
                return redirect()->back()->with('success', 'Tutup Buku (Rolling Batch) berhasil dijalankan! Silakan cek Laporan Perpetual.');
            } else {
                Log::error('Tutup Buku/Rolling Batch GAGAL. Output: ' . $output);
                return redirect()->back()->with('error', 'Tutup Buku GAGAL. Terjadi kesalahan. Cek log server.');
            }
        } catch (\Exception $e) {
            Log::error('Error saat eksekusi Rolling Batch: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error Sistem: ' . $e->getMessage());
        }
    }
}