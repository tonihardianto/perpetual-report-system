<?php

namespace App\Services;

use App\Models\BatchObat;
use App\Models\MasterObat;
use App\Models\StokPeriode;
use Illuminate\Support\Facades\DB;

/**
 * Service: StockService
 * -----------------------------------
 * Digunakan untuk proses perhitungan stok dan tutup bulan (carry-over)
 */
class StockService
{
    /**
     * Tutup bulan dan carry-over stok ke batch terbaru
     *
     * @param string $periode Format: YYYYMM
     * @return void
     */
    public function tutupBulan(string $periode)
    {
        DB::transaction(function () use ($periode) {
            $obatList = MasterObat::with('batches')->get();

            foreach ($obatList as $obat) {
                $totalSisa = $obat->batches->sum('stok_akhir');
                $batches = $obat->batches->sortByDesc('kode_batch');
                $sisa = $totalSisa;
                $distribusi = [];

                foreach ($batches as $batch) {
                    if ($sisa <= 0) {
                        $batch->stok_akhir = 0;
                    } elseif ($sisa >= $batch->stok_awal) {
                        $batch->stok_akhir = $batch->stok_awal;
                        $sisa -= $batch->stok_awal;
                    } else {
                        $batch->stok_akhir = $sisa;
                        $sisa = 0;
                    }

                    $batch->save();
                    $distribusi[$batch->kode_batch] = $batch->stok_akhir;
                }

                // Simpan hasil distribusi ke tabel stok_periode
                StokPeriode::create([
                    'id_obat' => $obat->id_obat,
                    'periode' => $periode,
                    'total_sisa' => $totalSisa,
                    'distribusi_json' => json_encode($distribusi),
                ]);
            }
        });
    }
}
