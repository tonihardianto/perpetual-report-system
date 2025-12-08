<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\BatchObat;
use App\Models\StockOpname;
use App\Models\StockOpnameHeader;
use App\Models\TransaksiMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StockOpnameController extends Controller
{
    /**
     * Menampilkan riwayat Input Sisa Stock yang sudah dilakukan.
     */
    public function index()
    {
        $riwayatHeaders = StockOpnameHeader::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(15);

        // Ambil ID semua obat yang aktif dan memiliki stok (master list)
        $totalAvailableObatIds = Obat::where('is_aktif', true)
            ->withSum(['batches as total_sisa_stok' => function($q) {
                $q->where('sisa_stok', '>', 0);
            }], 'sisa_stok')
            ->having('total_sisa_stok', '>', 0)
            ->pluck('id');

        // Tambahkan status dinamis ke setiap header
        $riwayatHeaders->getCollection()->transform(function ($header) use ($totalAvailableObatIds) {
            $bulan = $header->bulan;
            $tahun = $header->tahun;

            // Hitung obat yang sudah di-SO pada periode ini
            $processedObatCount = StockOpname::whereYear('tanggal_opname', $tahun)
                ->whereMonth('tanggal_opname', $bulan)
                ->join('batch_obat', 'stock_opname.batch_id', '=', 'batch_obat.id')
                ->whereIn('batch_obat.obat_id', $totalAvailableObatIds)
                ->distinct('batch_obat.obat_id')
                ->count('batch_obat.obat_id');

            if ($processedObatCount >= $totalAvailableObatIds->count()) {
                $header->dynamic_status = 'Selesai Penuh';
            } else {
                $header->dynamic_status = 'Selesai Sebagian';
            }
            return $header;
        });

        return view('transaksi.stock-opname.index', ['riwayatSO' => $riwayatHeaders]);
    }

    /**
     * Menampilkan form untuk memilih periode Input Sisa Stock.
     */
    public function create()
    {
        // Ambil semua ID obat yang aktif dan memiliki stok. Ini adalah daftar master obat yang harus di-SO.
        $allAvailableObatIds = Obat::where('is_aktif', true)
            ->withSum(['batches as total_sisa_stok' => function($q) {
                $q->where('sisa_stok', '>', 0);
            }], 'sisa_stok')
            ->having('total_sisa_stok', '>', 0)
            ->pluck('id');

        $donePeriods = StockOpnameHeader::select('bulan', 'tahun')->get();

        $bulanTahun = [];
        $currentDate = Carbon::now();

        // Generate 12 bulan ke belakang dari sekarang
        for ($i = 0; $i <= 12; $i++) {
            $date = $currentDate->copy()->subMonths($i);
            $bulan = $date->month;
            $tahun = $date->year;
            $value = $date->format('Y-m');
            $label = $date->translatedFormat('F Y');

            $isPartiallyDone = $donePeriods->where('bulan', $bulan)->where('tahun', '==', $tahun)->isNotEmpty();
            $isFullyDone = false;

            if ($isPartiallyDone) {
                // Cek apakah semua obat yang tersedia sudah di-SO pada periode ini.
                $remainingObatCount = Obat::whereIn('id', $allAvailableObatIds)
                    ->whereDoesntHave('batches.stockOpnames', function ($query) use ($tahun, $bulan) {
                        $query->whereYear('tanggal_opname', $tahun)->whereMonth('tanggal_opname', $bulan);
                    })
                    ->count();

                if ($remainingObatCount === 0) {
                    $isFullyDone = true;
                }
            }

            $bulanTahun[] = [
                'value' => $value,
                'label' => $label,
                'is_done' => $isPartiallyDone,
                'is_fully_done' => $isFullyDone,
                'disabled' => $isFullyDone, // Nonaktifkan jika sudah selesai penuh
            ];
        }

        return view('transaksi.stock-opname.create', compact('bulanTahun'));
    }

    /**
     * Menampilkan form SO parsial berdasarkan periode yang dipilih.
     */
    public function showForm(Request $request)
    {
        $validated = $request->validate(['periode' => 'required|date_format:Y-m']);

        $periode = Carbon::createFromFormat('Y-m', $validated['periode']);
        $bulan = $periode->month;
        $tahun = $periode->year;

        // Hanya menampilkan view kosong dengan informasi periode.
        // Data obat akan dimuat melalui AJAX.
        return view('transaksi.stock-opname.form-process', compact('bulan', 'tahun'));
    }

    /**
     * Menampilkan detail riwayat Input Sisa Stock untuk periode tertentu.
     */
    public function show(StockOpnameHeader $stockOpnameHeader)
    {
        $bulan = $stockOpnameHeader->bulan;
        $tahun = $stockOpnameHeader->tahun;

        // Ambil semua detail SO pada periode tersebut
        $detailSO = StockOpname::with(['batch.obat'])
            ->whereHas('batch.obat') // Pastikan relasi ada
            ->whereYear('tanggal_opname', $tahun)
            ->whereMonth('tanggal_opname', $bulan)
            ->orderBy('created_at', 'desc')
            ->get()
            // Group berdasarkan obat untuk menyederhanakan tampilan
            ->groupBy('batch.obat.nama_obat');

        return view('transaksi.stock-opname.show', compact('stockOpnameHeader', 'detailSO'));
    }

    /**
     * Menangani pencarian obat (AJAX) yang tersedia untuk SO pada periode tertentu.
     */
    public function searchObatForSo(Request $request)
    {
        $validated = $request->validate([
            'term' => 'nullable|string',
            'periode' => 'required|date_format:Y-m',
        ]);

        $searchTerm = $validated['term'] ?? '';
        $periode = Carbon::createFromFormat('Y-m', $validated['periode']);
        $bulan = $periode->month;
        $tahun = $periode->year;

        // 1. Ambil ID obat yang sudah di-SO pada periode ini untuk di-exclude
        $processedObatIds = StockOpname::whereYear('tanggal_opname', $tahun)
            ->whereMonth('tanggal_opname', $bulan)
            ->join('batch_obat', 'stock_opname.batch_id', '=', 'batch_obat.id')
            ->distinct()
            ->pluck('batch_obat.obat_id');

        // 2. Query dasar untuk mencari obat
        $query = Obat::where('is_aktif', true)
            ->whereNotIn('id', $processedObatIds) // <-- Filter utama: Jangan tampilkan yang sudah di-SO
            ->withSum(['batches as total_sisa_stok' => function ($q) {
                $q->where('sisa_stok', '>', 0);
            }], 'sisa_stok')
            ->orderBy('nama_obat');

        // 3. Terapkan filter pencarian
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_obat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kode_obat', 'like', '%' . $searchTerm . '%');
            });
        }

        $obats = $query->limit(20)->get();

        // 4. Format hasil untuk Select2
        $results = $obats->map(function ($obat) {
            return [
                'id' => $obat->id,
                'text' => '[' . $obat->kode_obat . '] ' . $obat->nama_obat,
                // Kirim data tambahan yang akan digunakan oleh JavaScript
                'kode_obat' => $obat->kode_obat,
                'nama_obat' => $obat->nama_obat,
                'total_sisa_stok' => $obat->total_sisa_stok ?? 0,
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Memproses hasil Input Sisa Stock dengan Jurnal Penutup/Pembuka.
     */
    public function process(Request $request)
    {
        $data = $request->validate([
            'periode_so' => 'required|string', // Format 'YYYY-MM'
            'opname_data' => 'required|array',
            'opname_data.*.obat_id' => 'required|exists:obat,id',
            'opname_data.*.stok_tercatat_sistem' => 'required|integer', 
            'opname_data.*.stok_fisik' => 'required|integer', 
            'opname_data.*.catatan' => 'nullable|string',
        ]);

        $periode = Carbon::createFromFormat('Y-m', $data['periode_so']);
        $bulanSO = $periode->month;
        $tahunSO = $periode->year;

        // Validasi stok fisik tidak boleh melebihi stok tercatat
        foreach ($data['opname_data'] as $index => $item) {
            if ($item['stok_fisik'] > $item['stok_tercatat_sistem']) {
                $obat = Obat::find($item['obat_id']);
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', "Input Sisa Stock gagal: Stok fisik ({$item['stok_fisik']}) untuk obat {$obat->nama_obat} tidak boleh melebihi stok tercatat sistem ({$item['stok_tercatat_sistem']})");
            }
        }

        $transactionsCount = 0;
        
        // Tanggal krusial berdasarkan periode yang dipilih
        $closingDate = Carbon::create($tahunSO, $bulanSO)->endOfMonth()->endOfDay();
        $openingDate = $closingDate->copy()->addDay()->startOfDay(); // Contoh: 01 Nov 2025 00:00:00

        DB::beginTransaction();
        try {
            $processedObatIdsForHeader = [];
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
                $processedObatIdsForHeader[] = $obat->id;
                
                $totalReduksi = $currentTotalStock - $stokFisik; 
                
                $batchesFIFO = $obat->batches()->where('sisa_stok', '>', 0)->orderBy('created_at', 'asc')->orderBy('id', 'asc')->get();
                $batchesLIFO = $obat->batches()->orderBy('created_at', 'desc')->orderBy('id', 'desc')->get(); 
                
                
                // --- FASE 1: MENCATAT KELUAR (KERUGIAN/KONSUMSI) ---
                $qtySisaReduksi = $totalReduksi;
                foreach ($batchesFIFO as $batch) {
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
            
            // Jika ada obat yang diproses, catat di header
            if (!empty($processedObatIdsForHeader)) {
                StockOpnameHeader::firstOrCreate(
                    ['bulan' => $bulanSO, 'tahun' => $tahunSO],
                    [
                        'status' => 'Selesai',
                        'tanggal_so_dilakukan' => now()
                    ]
                );
            }

            DB::commit();
            return redirect()->route('transaksi.stock-opname.index')->with('success', "Proses Input Sisa Stock periode {$periode->translatedFormat('F Y')} selesai. Total {$transactionsCount} transaksi mutasi dicatat.");

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
            'keterangan' => 'Sisa Stok Stok (' . $tipe . '): ' . $catatan,
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

    /**
     * Memproses "Tutup Bulan", yaitu melakukan SO otomatis untuk semua obat yang belum diproses.
     */
    public function closeMonth(Request $request)
    {
        $data = $request->validate([
            'periode' => 'required|date_format:Y-m',
        ]);

        $periode = Carbon::createFromFormat('Y-m', $data['periode']);
        $bulanSO = $periode->month;
        $tahunSO = $periode->year;

        // 1. Dapatkan semua obat yang aktif dan punya stok
        $allAvailableObatIds = Obat::where('is_aktif', true)
            ->withSum(['batches as total_sisa_stok' => function($q) {
                $q->where('sisa_stok', '>', 0);
            }], 'sisa_stok')
            ->having('total_sisa_stok', '>', 0)
            ->pluck('id');

        // 2. Dapatkan obat yang sudah di-SO di periode ini
        $processedObatIds = StockOpname::whereYear('tanggal_opname', $tahunSO)
            ->whereMonth('tanggal_opname', $bulanSO)
            ->join('batch_obat', 'stock_opname.batch_id', '=', 'batch_obat.id')
            ->distinct()
            ->pluck('batch_obat.obat_id');

        // 3. Dapatkan obat yang tersisa untuk diproses (yang belum di-SO)
        $remainingObats = Obat::whereIn('id', $allAvailableObatIds)
            ->whereNotIn('id', $processedObatIds)
            ->with('batches') // Eager load batches
            ->get();

        if ($remainingObats->isEmpty()) {
            return redirect()->route('transaksi.stock-opname.create')->with('info', 'Tidak ada obat sisa untuk diproses. Semua obat pada periode ini sudah di-Input Sisa Stock.');
        }

        $transactionsCount = 0;
        $closingDate = $periode->copy()->endOfMonth()->endOfDay();
        $openingDate = $closingDate->copy()->addDay()->startOfDay();

        DB::beginTransaction();
        try {
            foreach ($remainingObats as $obat) {
                $stokFisik = $obat->batches()->sum('sisa_stok');

                // Jika tidak ada stok, lewati (seharusnya tidak terjadi karena query awal)
                if ($stokFisik <= 0) continue;

                // Karena tidak ada selisih, FASE 1 (Konsumsi) tidak ada.
                // Langsung ke FASE 2, 3, 4 (Rolling Stok)

                $batchesLIFO = $obat->batches()->orderBy('tanggal_ed', 'desc')->get();
                
                // Tentukan Alokasi Akhir (sama dengan stok saat ini)
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
                        // FASE 2: Mencatat PENYESUAIAN MASUK (Laporan Excel Bulan Ini).
                        $transactionsCount += $this->createAdjustmentEntries($batch, $targetFinal, 'Tutup Bulan Otomatis', $closingDate);
                        
                        // FASE 3: Mencatat PENYESUAIAN KELUAR (Jurnal Penutup).
                        $transactionsCount += $this->createAdjustmentEntries($batch, $targetFinal * -1, 'Tutup Bulan Otomatis', $closingDate, 'Jurnal Penutup (Reversal)');
                        
                        // FASE 4: Mencatat MASUK (Jurnal Pembuka Bulan Depan).
                        $transactionsCount += $this->createOpeningPurchaseEntries($batch, $targetFinal, 'Tutup Bulan Otomatis', $openingDate);
                    }
                }
            }

            // Catat di header bahwa SO periode ini sudah dilakukan
            StockOpnameHeader::firstOrCreate(
                ['bulan' => $bulanSO, 'tahun' => $tahunSO],
                ['status' => 'Selesai', 'tanggal_so_dilakukan' => now()]
            );

            DB::commit();
            return redirect()->route('transaksi.stock-opname.index')->with('success', "Tutup bulan untuk periode {$periode->translatedFormat('F Y')} berhasil. " . $remainingObats->count() . " obat sisa telah diproses secara otomatis.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat proses Tutup Bulan SO: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Gagal memproses Tutup Bulan: ' . $e->getMessage());
        }
    }
}