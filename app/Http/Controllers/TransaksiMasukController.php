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
        $obats = Obat::where('is_aktif', true)->orderBy('nama_obat')->get();
        return view('transaksi.masuk.create', compact('obats'));
    }

    /**
     * Menyimpan Transaksi Masuk dan memperbarui stok perpetual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'nomor_batch' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'tanggal_ed' => 'required|date|after_or_equal:tanggal_masuk',
            'harga_beli_per_satuan' => 'required|numeric|min:0',
            'jumlah_unit' => 'required|integer|min:1',
            'referensi' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat atau Update Batch Obat (Data Kunci Perpetual)
            $batch = BatchObat::create([
                'obat_id' => $request->obat_id,
                'nomor_batch' => $request->nomor_batch,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_ed' => $request->tanggal_ed,
                'harga_beli_per_satuan' => $request->harga_beli_per_satuan,
                'stok_awal' => $request->jumlah_unit,
                'sisa_stok' => $request->jumlah_unit, // Stok langsung bertambah
            ]);

            // 2. Catat Transaksi Mutasi (Jurnal Perpetual)
            $totalHpp = $request->jumlah_unit * $request->harga_beli_per_satuan;
            
            TransaksiMutasi::create([
                'batch_id' => $batch->id,
                'tanggal_transaksi' => now(),
                'tipe_transaksi' => 'MASUK',
                'jumlah_unit' => $request->jumlah_unit,
                'harga_pokok_unit' => $request->harga_beli_per_satuan,
                'total_hpp' => $totalHpp,
                'referensi' => $request->referensi,
                'keterangan' => 'Pembelian/Penerimaan',
            ]);

            DB::commit();
            return redirect()->route('transaksi.masuk.create')->with('success', 'Transaksi masuk berhasil dicatat. Stok bertambah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }
}