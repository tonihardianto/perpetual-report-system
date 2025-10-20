<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\BatchObat;
use App\Models\StockOpname;
use App\Models\TransaksiMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StockOpnameController extends Controller
{
    /**
     * Menampilkan daftar Obat yang siap di Stock Opname.
     */
    public function index()
    {
        $obats = Obat::where('is_aktif', true)
            ->withSum(['batches as total_sisa_stok' => function($query) {
                $query->where('sisa_stok', '>', 0);
            }], 'sisa_stok')
            ->having('total_sisa_stok', '>', 0)
            ->orderBy('nama_obat')
            ->get();
            
        return view('transaksi.stock-opname.index', compact('obats'));
    }

    /**
     * Memproses hasil Stock Opname dengan Jurnal Penutup/Pembuka.
     */
    public function process(Request $request)
    {
        $data = $request->validate([
            'opname_data' => 'required|array',
            'opname_data.*.obat_id' => 'required|exists:obat,id',
            'opname_data.*.stok_tercatat_sistem' => 'required|integer', 
            'opname_data.*.stok_fisik' => 'required|integer', 
            'opname_data.*.catatan' => 'nullable|string',
        ]);

        $transactionsCount = 0;
        
        // --- PERBAIKAN LOGIKA TANGGAL KRUSIAL ---
        // Memaksa transaksi penutupan (Fase 1, 2, 3) terjadi di akhir bulan saat ini
        $closingDate = now()->endOfMonth()->endOfDay(); // Contoh: 31 Okt 2025 23:59:59
        
        // Memaksa transaksi pembukaan (Fase 4) terjadi di awal bulan berikutnya
        $openingDate = $closingDate->copy()->addDay()->startOfDay(); // Contoh: 01 Nov 2025 00:00:00

        DB::beginTransaction();
        try {
            foreach ($data['opname_data'] as $item) {
                $obat = Obat::find($item['obat_id']);
                
                // 1. Validasi Keamanan & Persiapan Data
                $currentTotalStock = $obat->batches()->sum('sisa_stok');
                $stokTercatatDariForm = $item['stok_tercatat_sistem'];
                $stokFisik = $item['stok_fisik'];

                if ($stokTercatatDariForm != $currentTotalStock) {
                     DB::rollBack();
                     return redirect()->back()->with('error', 'Stok sistem untuk obat ' . $obat->nama_obat . ' telah berubah sejak Anda memulai SO. Mohon ulangi proses.')->withInput();
                }

                if ($stokTercatatDariForm == $stokFisik) continue;
                
                $totalReduksi = $currentTotalStock - $stokFisik; 
                
                $batchesFEFO = $obat->batches()->where('sisa_stok', '>', 0)->orderBy('tanggal_ed', 'asc')->get();
                $batchesLIFO = $obat->batches()->orderBy('tanggal_ed', 'desc')->get(); 
                
                
                // --- FASE 1: MENCATAT KELUAR (KERUGIAN/KONSUMSI) ---
                $qtySisaReduksi = $totalReduksi;
                foreach ($batchesFEFO as $batch) {
                    if ($qtySisaReduksi <= 0) break; 
                    
                    $qtyKurangi = min($batch->sisa_stok, $qtySisaReduksi);
                    
                    if ($qtyKurangi > 0) {
                        // Gunakan $closingDate (Akhir Bulan Ini)
                        $transactionsCount += $this->createConsumptionEntries($batch, $qtyKurangi, $item['catatan'], $closingDate);
                        
                        $batch->sisa_stok -= $qtyKurangi;
                        $batch->save();
                        
                        $qtySisaReduksi -= $qtyKurangi;
                    }
                }
                
                // --- FASE 2 & 3 & 4: JURNAL PENUTUP & PEMBUKA (ROLLING) ---
                
                // 2a. Tentukan Alokasi Akhir 
                $alokasiAkhir = [];
                $sisaAlokasi = $stokFisik;
                
                foreach ($batchesLIFO as $batch) {
                    if ($sisaAlokasi <= 0) {
                        $alokasiAkhir[$batch->id] = 0;
                    } else {
                        $maksAlokasi = $batch->stok_awal;
                        $dialokasikan = min($sisaAlokasi, $maksAlokasi);
                        $alokasiAkhir[$batch->id] = $dialokasikan;
                        $sisaAlokasi -= $dialokasikan;
                    }
                }
                
                // Iterasi untuk semua batch yang mendapatkan alokasi
                foreach ($batchesLIFO as $batch) {
                    $targetFinal = $alokasiAkhir[$batch->id] ?? 0;
                    
                    if ($targetFinal > 0) {
                        // FASE 2: Mencatat PENYESUAIAN MASUK (Laporan Excel Bulan Ini). Gunakan $closingDate
                        $transactionsCount += $this->createAdjustmentEntries($batch, $targetFinal, $item['catatan'], $closingDate);
                        
                        // FASE 3: Mencatat PENYESUAIAN KELUAR (Jurnal Penutup). Gunakan $closingDate
                        $transactionsCount += $this->createAdjustmentEntries($batch, $targetFinal * -1, $item['catatan'], $closingDate, 'Jurnal Penutup (Reversal)');
                        
                        // FASE 4: Mencatat MASUK (Jurnal Pembuka Bulan Depan). Gunakan $openingDate
                        $transactionsCount += $this->createOpeningPurchaseEntries($batch, $targetFinal, $item['catatan'], $openingDate);
                    }
                }
                
                // --- KOREKSI FINAL SISA STOK DI DATABASE ---
                foreach ($obat->batches()->get() as $batch) {
                    $batch->sisa_stok = $alokasiAkhir[$batch->id] ?? 0;
                    $batch->save();
                }
            }
            
            DB::commit();
            return redirect()->route('transaksi.stock-opname.index')->with('success', "Proses Stock Opname & Jurnal Pembuka selesai. Total {$transactionsCount} transaksi mutasi dicatat.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memproses SO: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Gagal memproses SO: ' . $e->getMessage());
        }
    }
    
    // Helper function untuk FASE 1: KELUAR (Konsumsi)
    private function createConsumptionEntries($batch, $qtyKeluar, $catatan, $date)
    {
        $hppUnit = $batch->harga_beli_per_satuan;
        $totalHpp = $qtyKeluar * $hppUnit;
        
        TransaksiMutasi::create([
            'batch_id' => $batch->id,
            'tanggal_transaksi' => $date,
            'tipe_transaksi' => 'KELUAR',
            'jumlah_unit' => $qtyKeluar * -1,
            'harga_pokok_unit' => $hppUnit,
            'total_hpp' => $totalHpp * -1,
            'referensi' => 'SO-' . $date->format('YmdHi'),
            'keterangan' => 'Konsumsi (Kerugian/Loss) dari SO: ' . $catatan,
        ]);
        return 1;
    }
    
    // Helper function untuk FASE 2 & 3: PENYESUAIAN (MASUK & KELUAR)
    private function createAdjustmentEntries($batch, $qty, $catatan, $date, $tipe = 'Sisa Fisik untuk Reporting')
    {
        $nilai = $qty * $batch->harga_beli_per_satuan;
        $referensi = 'SO-' . $date->format('YmdHi');
        $isMasuk = $qty > 0;
        
        if ($isMasuk) {
             // Hanya catat Audit Trail untuk entri MASUK/Reporting (Fase 2)
             StockOpname::create([
                'batch_id' => $batch->id,
                'tanggal_opname' => $date->toDateString(),
                'stok_tercatat_sistem' => $batch->sisa_stok, 
                'stok_fisik' => $batch->sisa_stok + $qty, 
                'selisih' => $qty, 
                'nilai_selisih' => $nilai,
                'catatan' => $catatan . " (Alokasi Sisa Fisik)",
            ]);
        }

        TransaksiMutasi::create([
            'batch_id' => $batch->id,
            'tanggal_transaksi' => $date,
            'tipe_transaksi' => 'PENYESUAIAN',
            'jumlah_unit' => $qty, 
            'harga_pokok_unit' => $batch->harga_beli_per_satuan,
            'total_hpp' => $nilai,
            'referensi' => $referensi,
            'keterangan' => 'Penyesuaian Stok (' . $tipe . '): ' . $catatan,
        ]);
        return 1;
    }
    
    // Helper function untuk FASE 4: MASUK (Jurnal Pembuka)
    private function createOpeningPurchaseEntries($batch, $qtyMasuk, $catatan, $date)
    {
        $hppUnit = $batch->harga_beli_per_satuan;
        $totalHpp = $qtyMasuk * $hppUnit;
        
        TransaksiMutasi::create([
            'batch_id' => $batch->id,
            'tanggal_transaksi' => $date,
            'tipe_transaksi' => 'MASUK',
            'jumlah_unit' => $qtyMasuk,
            'harga_pokok_unit' => $hppUnit,
            'total_hpp' => $totalHpp,
            'referensi' => 'OP-SO-' . $date->format('YmdHi'),
            'keterangan' => 'Pembelian Awal (Stok Awal Bulan) dari SO sebelumnya: ' . $catatan,
        ]);
        return 1;
    }
}