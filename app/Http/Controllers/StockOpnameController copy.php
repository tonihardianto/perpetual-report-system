<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\BatchObat;
use App\Models\StockOpname;
use App\Models\TransaksiMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    /**
     * Menampilkan daftar Batch yang siap di Stock Opname.
     */
    // app/Http/Controllers/StockOpnameController.php (Index Method)

    public function index()
    {
        // Ambil semua Obat yang memiliki sisa stok > 0
        $obats = Obat::where('is_aktif', true)
            // Gunakan withSum untuk menghitung total stok dari semua batch yang memiliki stok > 0
            ->withSum(['batches as total_sisa_stok' => function($query) {
                $query->where('sisa_stok', '>', 0);
            }], 'sisa_stok')
            ->having('total_sisa_stok', '>', 0)
            ->orderBy('nama_obat')
            ->get();
            
        // Kita tidak lagi mengirim $batches, hanya $obats
        return view('transaksi.stock-opname.index', compact('obats'));
    }

    /**
     * Memproses hasil Stock Opname.
     */
    // public function process(Request $request)
    // {
    //     $data = $request->validate([
    //         'opname_data' => 'required|array',
    //         'opname_data.*.batch_id' => 'required|exists:batch_obat,id',
    //         'opname_data.*.stok_tercatat_sistem' => 'required|integer',
    //         'opname_data.*.stok_fisik' => 'required|integer',
    //         'opname_data.*.catatan' => 'nullable|string',
    //     ]);

    //     $processedCount = 0;

    //     DB::beginTransaction();
    //     try {
    //         foreach ($data['opname_data'] as $item) {
    //             $batch = BatchObat::find($item['batch_id']);
    //             $selisih = $item['stok_fisik'] - $item['stok_tercatat_sistem'];
                
    //             // Hanya proses Batch yang memiliki selisih (fisik tidak sama dengan sistem)
    //             if ($selisih != 0) {
    //                 // Pastikan stok tercatat sistem tidak berubah saat proses
    //                 if ($item['stok_tercatat_sistem'] != $batch->sisa_stok) {
    //                      // Lemparkan error jika ada perubahan stok saat user input dan sebelum proses
    //                      DB::rollBack();
    //                      return redirect()->back()->with('error', 'Stok sistem untuk batch ' . $batch->nomor_batch . ' telah berubah sejak Anda memulai SO. Mohon ulangi proses.');
    //                 }
                    
    //                 // 1. Catat Audit Trail di tabel StockOpname
    //                 $nilaiSelisih = $selisih * $batch->harga_beli_per_satuan;

    //                 StockOpname::create([
    //                     'batch_id' => $batch->id,
    //                     'tanggal_opname' => now()->toDateString(),
    //                     'stok_tercatat_sistem' => $item['stok_tercatat_sistem'],
    //                     'stok_fisik' => $item['stok_fisik'],
    //                     'selisih' => $selisih,
    //                     'nilai_selisih' => $nilaiSelisih,
    //                     'catatan' => $item['catatan'],
    //                 ]);
                    
    //                 // 2. Catat Jurnal Perpetual (Transaksi Mutasi Tipe PENYESUAIAN)
    //                 TransaksiMutasi::create([
    //                     'batch_id' => $batch->id,
    //                     'tanggal_transaksi' => now(),
    //                     'tipe_transaksi' => 'PENYESUAIAN',
    //                     'jumlah_unit' => $selisih, // Selisih bisa positif (gain) atau negatif (loss)
    //                     'harga_pokok_unit' => $batch->harga_beli_per_satuan,
    //                     'total_hpp' => $nilaiSelisih,
    //                     'referensi' => 'SO-' . now()->format('YmdHi'),
    //                     'keterangan' => $selisih > 0 ? 'Penyesuaian Stok (Kelebihan)' : 'Penyesuaian Stok (Kekurangan/Loss)',
    //                 ]);
                    
    //                 // 3. Update Stok Batch (Perpetual)
    //                 $batch->sisa_stok = $item['stok_fisik'];
    //                 $batch->save();
                    
    //                 $processedCount++;
    //             }
    //         }
    //         DB::commit();
    //         return redirect()->route('transaksi.stock-opname.index')->with('success', "Proses Stock Opname berhasil. {$processedCount} batch disesuaikan.");
            
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->withInput()->with('error', 'Gagal memproses SO: ' . $e->getMessage());
    //     }
    // }

    // app/Http/Controllers/StockOpnameController.php (Process Method)

    public function process(Request $request)
    {
        $data = $request->validate([
            'opname_data' => 'required|array',
            'opname_data.*.obat_id' => 'required|exists:obat,id',
            'opname_data.*.stok_tercatat_sistem' => 'required|integer',
            'opname_data.*.stok_fisik' => 'required|integer',
            'opname_data.*.catatan' => 'nullable|string',
        ]);

        $processedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($data['opname_data'] as $item) {
                $obat = Obat::find($item['obat_id']);
                $selisihTotal = $item['stok_fisik'] - $item['stok_tercatat_sistem'];
                $qtyUntukDisesuaikan = abs($selisihTotal);
                
                // Hanya proses jika ada selisih
                if ($selisihTotal != 0 && $qtyUntukDisesuaikan > 0) {
                    
                    // Ambil Batch yang masih memiliki stok untuk Obat ini
                    // LIFO/Reverse FEFO: Batch Terbaru (tanggal_masuk/tanggal_ed terjauh) disesuaikan duluan
                    $batches = $obat->batches()
                                    ->where('sisa_stok', '>', 0)
                                    ->orderBy('tanggal_ed', $selisihTotal > 0 ? 'desc' : 'asc') // FIFO untuk loss, LIFO untuk gain
                                    ->get();

                    foreach ($batches as $batch) {
                        if ($qtyUntukDisesuaikan <= 0) break; // Sisa selisih sudah habis

                        $stokBatchSaatIni = $batch->sisa_stok;
                        
                        if ($selisihTotal > 0) { // KELEBIHAN (GAIN): Tambah Stok dari Batch Terbaru/ED Terjauh
                            
                            // Alokasikan sisa selisih yang harus ditambahkan
                            $alokasiUnit = $qtyUntukDisesuaikan;
                            
                            $batch->sisa_stok += $alokasiUnit;
                            $batch->save();
                            
                            $processedCount += $this->createAdjustmentEntries($batch, $alokasiUnit, 'GAIN', $item['catatan']);
                            $qtyUntukDisesuaikan = 0; // Selisih teratasi di batch ini (atau batch pertama jika gain)
                            
                        } else { // KEKURANGAN (LOSS): Kurangi Stok, mulai dari FEFO (Batch Terdekat/Terlama)
                            
                            // Kuantitas yang diambil dari batch ini adalah minimal antara Stok Batch dan Selisih yang harus ditutup
                            $kurangiUnit = min($stokBatchSaatIni, $qtyUntukDisesuaikan);
                            
                            $batch->sisa_stok -= $kurangiUnit;
                            $batch->save();
                            
                            $processedCount += $this->createAdjustmentEntries($batch, $kurangiUnit * -1, 'LOSS', $item['catatan']);
                            $qtyUntukDisesuaikan -= $kurangiUnit;
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->route('transaksu.stock-opname.index')->with('success', "Proses Stock Opname berhasil. {$processedCount} penyesuaian dicatat.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memproses SO: ' . $e->getMessage());
        }
    }


    /**
     * Helper function untuk mencatat mutasi dan SO trail.
     */
    private function createAdjustmentEntries($batch, $selisih, $tipe, $catatan)
    {
        $nilaiSelisih = $selisih * $batch->harga_beli_per_satuan;
        $referensi = 'SO-' . now()->format('YmdHi');

        // 1. Catat Audit Trail di tabel StockOpname (Kita hanya mencatat Net Adjustment di sini)
        StockOpname::create([
            'batch_id' => $batch->id,
            'tanggal_opname' => now()->toDateString(),
            // Karena ini per Batch Adjustment, stok sistem/fisik hanya di level Batch.
            'stok_tercatat_sistem' => $batch->sisa_stok - $selisih, // Sebelum penyesuaian
            'stok_fisik' => $batch->sisa_stok, // Setelah penyesuaian
            'selisih' => $selisih, 
            'nilai_selisih' => $nilaiSelisih,
            'catatan' => $catatan . " (Alokasi {$tipe})",
        ]);

        // 2. Catat Jurnal Perpetual (PENYESUAIAN)
        TransaksiMutasi::create([
            'batch_id' => $batch->id,
            'tanggal_transaksi' => now(),
            'tipe_transaksi' => 'PENYESUAIAN',
            'jumlah_unit' => $selisih, 
            'harga_pokok_unit' => $batch->harga_beli_per_satuan,
            'total_hpp' => $nilaiSelisih,
            'referensi' => $referensi,
            'keterangan' => $tipe == 'GAIN' ? 'Penyesuaian Stok (Kelebihan)' : 'Penyesuaian Stok (Kekurangan/Loss)',
        ]);
        return 1;
    }
}