<?php

namespace App\Console\Commands;

use App\Models\Obat;
use App\Models\TransaksiMutasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetBatchStok extends Command
{
    /**
     * Nama dan signature dari console command.
     * @var string
     */
    protected $signature = 'inventory:reset-batch-stok';

    /**
     * Deskripsi console command.
     * @var string
     */
    protected $description = 'Menghitung sisa stok akhir bulan dan mengalokasikannya kembali ke batch terbaru (Custom Rolling Batch).';

    /**
     * Eksekusi console command.
     */
    public function handle()
    {
        $this->info('Memulai proses Custom Rolling Batch Akhir Bulan...');

        $obats = Obat::where('is_aktif', true)->get();
        $totalProcessed = 0;

        DB::beginTransaction();
        try {
            foreach ($obats as $obat) {
                // 1. Hitung total sisa stok netto saat ini dari semua batch obat ini
                $totalStokNettoSaatIni = $obat->batches()->sum('sisa_stok');
                $sisaStokUntukDialokasikan = $totalStokNettoSaatIni;

                if ($sisaStokUntukDialokasikan <= 0) {
                    $this->comment("Obat: {$obat->nama_obat} - Tidak ada sisa stok netto, melewati proses rolling.");
                    continue;
                }
                
                $this->line("Memproses {$obat->nama_obat} (Total Stok Netto: {$totalStokNettoSaatIni} unit)");

                // 2. Kumpulkan semua Batch yang pernah ada (termasuk yang stoknya 0)
                $allBatches = $obat->batches()->orderBy('tanggal_ed', 'asc')->get();
                
                // --- ZERO-OUT MASSAL ---
                // Nol-kan semua sisa stok batch yang > 0 dan catat sebagai PENYESUAIAN KELUAR
                $batchesDiNolkan = 0;
                foreach ($allBatches as $batch) {
                    if ($batch->sisa_stok > 0) {
                        $qtyZeroOut = $batch->sisa_stok;
                        
                        // Catat sebagai PENYESUAIAN KELUAR (Zero-Out)
                        $this->createAdjustmentEntry($batch, $qtyZeroOut * -1, 'STOK-ZERO-OUT');
                        
                        // Update stok di database menjadi 0
                        $batch->sisa_stok = 0;
                        $batch->save();
                        
                        $batchesDiNolkan++;
                    }
                }
                $this->line("  -> {$batchesDiNolkan} batch dinolkan (Zero-Out).");
                
                // --- RE-ALOKASI NETTO ---
                // Sekarang, alokasikan $sisaStokUntukDialokasikan (misal 350 unit) ke batch terbaru
                $batchesUntukAlokasi = $obat->batches()->orderBy('tanggal_ed', 'desc')->get();
                $batchesDialokasikan = 0;
                
                foreach ($batchesUntukAlokasi as $batch) {
                    if ($sisaStokUntukDialokasikan <= 0) break;

                    $stokAwalMaks = $batch->stok_awal; // Batas maksimal per batch (contoh: 200 unit)
                    $dialokasikan = min($sisaStokUntukDialokasikan, $stokAwalMaks);

                    // Catat Penyesuaian MASUK (Re-alokasi)
                    $this->createAdjustmentEntry($batch, $dialokasikan, 'STOK-AWAL-ROLLING');

                    // Update stok batch di database
                    $batch->sisa_stok = $dialokasikan;
                    $batch->save();

                    $sisaStokUntukDialokasikan -= $dialokasikan;
                    $batchesDialokasikan++;
                }

                $this->info("  -> Total {$totalStokNettoSaatIni} unit dialokasikan kembali ke {$batchesDialokasikan} batch.");
                $totalProcessed++;
            }

            DB::commit();
            $this->info("Proses Custom Rolling Batch berhasil. Total {$totalProcessed} obat diproses.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Gagal memproses Rolling Batch: ' . $e->getMessage());
            return 1; // Return error code
        }

        return 0; // Return success code
    }
    
    /**
     * Helper function untuk mencatat transaksi mutasi tipe PENYESUAIAN.
     */
    private function createAdjustmentEntry($batch, $jumlahUnit, $referensi)
    {
        $tipeMutasi = $jumlahUnit > 0 ? 'Sisa Stok Stok (Kelebihan/Masuk)' : 'Sisa Stok Stok (Kekurangan/Keluar)';
        
        TransaksiMutasi::create([
            'batch_id' => $batch->id,
            'tanggal_transaksi' => now(),
            'tipe_transaksi' => 'PENYESUAIAN',
            'jumlah_unit' => $jumlahUnit, 
            'harga_pokok_unit' => $batch->harga_beli_per_satuan,
            'total_hpp' => $jumlahUnit * $batch->harga_beli_per_satuan,
            'referensi' => $referensi,
            'keterangan' => $tipeMutasi . ' - Rolling Batch Akhir Bulan',
        ]);
        
        return 1;
    }
}