<?php

namespace App\Services;

use App\Models\BatchObat;
use App\Models\TransaksiMutasi;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Mengambil stok berdasarkan prinsip FEFO (First-Expired, First-Out).
     * * @param int $obatId ID dari Master Obat.
     * @param int $quantity Jumlah unit yang dibutuhkan.
     * @return array Array batch yang dialokasikan, atau throw Exception jika stok kurang.
     */
    public function allocateStock(int $obatId, int $quantity): array
    {
        // 1. Ambil Batch yang Tersedia (FEFO: ED terdekat diambil duluan)
        $availableBatches = BatchObat::where('obat_id', $obatId)
            ->where('sisa_stok', '>', 0)
            ->orderBy('tanggal_ed', 'asc') // FEFO Logic
            ->orderBy('tanggal_masuk', 'asc') // FIFO Tie-breaker
            ->get();

        if ($availableBatches->sum('sisa_stok') < $quantity) {
            throw new \Exception("Stok Obat tidak mencukupi. Tersedia: " . $availableBatches->sum('sisa_stok'));
        }

        $allocatedBatches = [];
        $remainingQuantity = $quantity;

        // 2. Alokasikan stok dari batch-batch yang tersedia
        foreach ($availableBatches as $batch) {
            if ($remainingQuantity <= 0) break;

            $take = min($remainingQuantity, $batch->sisa_stok);
            
            $allocatedBatches[] = [
                'batch_id' => $batch->id,
                'quantity' => $take,
                'hpp_unit' => $batch->harga_beli_per_satuan, // HPP dari batch
            ];

            $remainingQuantity -= $take;
        }

        return $allocatedBatches;
    }

    /**
     * Melakukan update stok dan mencatat transaksi mutasi (Jurnal Perpetual).
     * * @param array $allocations Hasil dari allocateStock().
     * @param string $referensi No. Referensi Transaksi.
     * @param float $hargaJual Harga Jual per unit (jika ada).
     * @return bool
     */
    public function processStockOut(array $allocations, string $referensi, float $hargaJual = null): bool
    {
        DB::beginTransaction();
        try {
            foreach ($allocations as $item) {
                $batch = BatchObat::find($item['batch_id']);
                
                // 1. Update Stok Batch (Perpetual - Real-time)
                $batch->sisa_stok -= $item['quantity'];
                $batch->save();

                // 2. Catat Transaksi Mutasi (Jurnal Perpetual)
                $hppTotal = $item['quantity'] * $item['hpp_unit'];
                
                TransaksiMutasi::create([
                    'batch_id' => $item['batch_id'],
                    'tanggal_transaksi' => now(),
                    'tipe_transaksi' => 'KELUAR',
                    'jumlah_unit' => $item['quantity'] * -1, // Simpan sebagai negatif
                    'harga_pokok_unit' => $item['hpp_unit'],
                    'total_hpp' => $hppTotal * -1, // Simpan sebagai negatif
                    'harga_jual_unit' => $hargaJual,
                    'referensi' => $referensi,
                    'keterangan' => 'Pemakaian/Penjualan',
                ]);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Biasanya log error $e
            return false;
        }
    }
}