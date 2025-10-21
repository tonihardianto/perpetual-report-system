<?php

namespace App\Http\Controllers;
use App\Models\Obat;
use App\Models\BatchObat;
use App\Models\TransaksiMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiMasukController extends Controller
{
    /**
     * Menampilkan form input Transaksi Masuk (Pembelian).
     */
    public function create()
    {
        return view('transaksi.masuk.create');
    }

    /**
     * Menyimpan Transaksi Masuk dan memperbarui stok perpetual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'referensi' => 'required|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.obat_id' => 'required|exists:obat,id',
            'items.*.nomor_batch' => 'required|string|max:100',
            'items.*.tanggal_ed' => 'required|date|after_or_equal:tanggal_masuk',
            'items.*.harga_beli_per_satuan' => 'required|numeric|min:0',
            'items.*.jumlah_unit' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                // 1. Buat atau Update Batch Obat untuk setiap item
                $batch = BatchObat::create([
                    'obat_id' => $item['obat_id'],
                    'nomor_batch' => $item['nomor_batch'],
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'tanggal_ed' => $item['tanggal_ed'],
                    'harga_beli_per_satuan' => $item['harga_beli_per_satuan'],
                    'stok_awal' => $item['jumlah_unit'],
                    'sisa_stok' => $item['jumlah_unit'], // Stok langsung bertambah
                ]);

                // 2. Catat Transaksi Mutasi untuk setiap item
                $totalHpp = $item['jumlah_unit'] * $item['harga_beli_per_satuan'];
                
                TransaksiMutasi::create([
                    'batch_id' => $batch->id,
                    'tanggal_transaksi' => $request->tanggal_masuk,
                    'tipe_transaksi' => 'MASUK',
                    'jumlah_unit' => $item['jumlah_unit'],
                    'harga_pokok_unit' => $item['harga_beli_per_satuan'],
                    'total_hpp' => $totalHpp,
                    'referensi' => $request->referensi,
                    'keterangan' => 'Pembelian/Penerimaan',
                ]);
            }

            DB::commit();
            return redirect()->route('transaksi.masuk.create')
                ->with('success', 'Semua transaksi masuk berhasil dicatat. Stok bertambah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }
}