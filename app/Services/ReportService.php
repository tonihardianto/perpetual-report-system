<?php

namespace App\Services;

use App\Models\BatchObat;
use App\Models\TransaksiMutasi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Menghasilkan data laporan perpetual tahunan (format horizontal).
     */
    public function generatePerpetualReport(int $year, array $obatIds = []): array
    {
        // Eager load 'transaksiMutasi' dan 'obat'
        $query = BatchObat::with(['obat', 'transaksiMutasi']);
        
        // Filter by obat_ids if provided
        if (!empty($obatIds)) {
            $query->whereHas('obat', function($q) use ($obatIds) {
                $q->whereIn('id', $obatIds);
            });
        }
        
        // Filter batch yang memiliki transaksi di tahun yang dipilih ATAU sebelumnya
        // (Untuk menghitung saldo awal dan mutasi di tahun tersebut)
        $query->whereHas('transaksiMutasi', function($q) use ($year) {
            $q->whereYear('tanggal_transaksi', '<=', $year);
        });
        
        // Gunakan withAggregate untuk mengurutkan berdasarkan nama obat
        $batches = $query->select('batch_obat.*')
            ->with(['obat' => function($query) {
                $query->orderBy('nama_obat');
            }])
            ->join('obat', 'batch_obat.obat_id', '=', 'obat.id')
            ->orderBy('obat.nama_obat')
            ->orderBy('batch_obat.tanggal_ed')
            ->get();
        
        $reportData = [];
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        foreach ($batches as $batch) {
            
            // Menggunakan operator ternary (universal) untuk akses aman
            $obatNama = $batch->obat 
                        ? $batch->obat->nama_obat 
                        : 'OBAT TIDAK DITEMUKAN (ID: ' . ($batch->obat_id ?? 'N/A') . ')'; 
            
            $allMutations = $batch->transaksiMutasi ?? collect(); 
            $allMutations = $allMutations->sortBy('tanggal_transaksi'); 

            $currentData = [
                'obat_nama' => $obatNama, 
                'batch_no' => $batch->nomor_batch,
                'ed' => $batch->tanggal_ed->format('Y-m-d'),
                'tanggal_masuk' => $batch->tanggal_masuk ? $batch->tanggal_masuk->format('Y-m-d') : '-',
                'hpp_unit' => $batch->harga_beli_per_satuan,
                'saldo_awal_qty' => 0,
                'saldo_awal_value' => 0,
                'months' => [],
            ];

            $previousQty = 0;
            $previousValue = 0;

            // 1. Hitung Saldo Awal (Sebelum 1 Januari tahun ini)
            $saldoAwalMutations = $allMutations->filter(function($m) use ($year) {
                $tanggal = $m->tanggal_transaksi instanceof Carbon ? $m->tanggal_transaksi : Carbon::parse($m->tanggal_transaksi);
                return $tanggal->year < $year;
            });

            $previousQty = $saldoAwalMutations->sum('jumlah_unit');
            $previousValue = $saldoAwalMutations->sum('total_hpp');

            $currentData['saldo_awal_qty'] = $previousQty;
            $currentData['saldo_awal_value'] = $previousValue;

            // 2. Loop per bulan
            for ($i = 1; $i <= 12; $i++) {
                $startOfMonth = Carbon::create($year, $i, 1)->startOfDay();
                $endOfMonth = Carbon::create($year, $i, 1)->endOfMonth()->endOfDay();

                $mutations = $allMutations->filter(function($m) use ($startOfMonth, $endOfMonth) {
                    $tanggal = $m->tanggal_transaksi instanceof Carbon ? $m->tanggal_transaksi : Carbon::parse($m->tanggal_transaksi);
                    return $tanggal->between($startOfMonth, $endOfMonth);
                });

                // --- LOGIKA AGREGASI CUSTOM ---
                
                // A. KELUAR (Konsumsi) - KELUAR adalah KELUAR dari SO (Loss)
                $keluarMutasi = $mutations->where('tipe_transaksi', 'KELUAR');
                $keluarQty = abs($keluarMutasi->sum('jumlah_unit')); 
                $keluarValue = abs($keluarMutasi->sum('total_hpp'));

                // B. MASUK (Pembelian Normal + Jurnal Pembuka SO)
                $masukMutasi = $mutations->where('tipe_transaksi', 'MASUK');
                
                // Jurnal Pembuka diidentifikasi melalui referensi 'OP-SO-'
                $jurnalPembuka = $masukMutasi->filter(function($m) {
                    return str_starts_with($m->referensi, 'OP-SO-');
                });
                
                // Masuk Qty = Pembelian Biasa + Jurnal Pembuka
                $masukQty = $masukMutasi->sum('jumlah_unit');
                $masukValue = $masukMutasi->sum('total_hpp');

                // C. PENYESUAIAN (Sisa Stok Fisik untuk Laporan)
                $penyesuaianMutasi = $mutations->where('tipe_transaksi', 'PENYESUAIAN');
                
                // Filter: Ambil hanya PENYESUAIAN MASUK (Positif) yang berasal dari FASE 2 SO
                $penyesuaianCustom = $penyesuaianMutasi->filter(function($m) {
                    // Hanya transaksi PENYESUAIAN dengan jumlah unit > 0
                    if ($m->jumlah_unit <= 0) return false; 
                    
                    // Harus memiliki keterangan 'Sisa Fisik untuk Reporting' (Fase 2 SO)
                    if (strpos($m->keterangan, 'Sisa Fisik untuk Reporting') === false) return false;
                    
                    // Jurnal Penutup (Fase 3) diabaikan karena jumlah unitnya negatif
                    return true;
                });
                
                $penyesuaianQty = $penyesuaianCustom->sum('jumlah_unit');
                $penyesuaianValue = $penyesuaianCustom->sum('total_hpp');

                // D. Perhitungan Saldo Akhir (Menggunakan NET CHANGE dari SEMUA transaksi)
                // Transaksi PENYESUAIAN FASE 2 (+X) dan FASE 3 (-X) akan menghasilkan Net Change = 0
                $netChange = $mutations->sum('jumlah_unit');
                
                $currentQty = $previousQty + $netChange;
                $currentValue = $previousValue + $mutations->sum('total_hpp');

                $currentData['months'][$i] = [
                    'nama' => $bulanNama[$i],
                    'masuk_qty' => $masukQty,
                    'masuk_value' => $masukValue,
                    'keluar_qty' => $keluarQty,
                    'keluar_value' => $keluarValue,
                    'penyesuaian_qty' => $penyesuaianQty,
                    'penyesuaian_value' => $penyesuaianValue,
                    'saldo_akhir_qty' => $currentQty,
                    'saldo_akhir_value' => $currentValue,
                ];

                $previousQty = $currentQty;
                $previousValue = $currentValue;
            }

            // Hanya tambahkan ke reportData jika ada aktivitas di tahun ini
            // (Memiliki saldo awal atau ada mutasi di bulan tertentu)
            $hasActivity = $currentData['saldo_awal_qty'] != 0 || $currentData['saldo_awal_value'] != 0;
            
            if (!$hasActivity) {
                foreach ($currentData['months'] as $monthData) {
                    if ($monthData['masuk_qty'] != 0 || $monthData['keluar_qty'] != 0 || $monthData['penyesuaian_qty'] != 0) {
                        $hasActivity = true;
                        break;
                    }
                }
            }
            
            if ($hasActivity) {
                $reportData[] = $currentData;
            }
        }

        // Urutkan array hasil berdasarkan nama obat (jika ada data)
        if (!empty($reportData)) {
            usort($reportData, function($a, $b) {
                // Pastikan kedua nilai ada sebelum membandingkan
                $namaA = $a['obat_nama'] ?? '';
                $namaB = $b['obat_nama'] ?? '';
                return strcasecmp($namaA, $namaB);
            });
        }

        return $reportData;
    }
}