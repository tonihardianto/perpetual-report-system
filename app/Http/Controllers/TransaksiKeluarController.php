<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiKeluarController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        // Inject InventoryService
        $this->inventoryService = $inventoryService;
    }

    /**
     * Menampilkan form input Transaksi Keluar (Pemakaian/Penjualan).
     */
    public function create()
    {
        $obats = Obat::where('is_aktif', true)->orderBy('nama_obat')->get();
        // Anda mungkin perlu tabel 'Unit' atau 'Departemen' RS di sini
        $unit_pengguna = [
            'Farmasi Rawat Inap', 
            'Farmasi Rawat Jalan', 
            'UGD', 
            'ICU', 
            'Penjualan Umum'
        ]; 

        return view('transaksi.keluar.create', compact('obats', 'unit_pengguna'));
    }

    /**
     * Menyimpan Transaksi Keluar dan menjalankan logika FEFO perpetual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah_unit' => 'required|integer|min:1',
            'referensi' => 'required|string|max:100', // Contoh: Nomor Resep, Nomor Bon Permintaan Unit
            'unit_penerima' => 'required|string', 
            'harga_jual_unit' => 'nullable|numeric|min:0', // Jika ada komponen penjualan
        ]);

        try {
            // 1. Alokasi Stok menggunakan Logika FEFO
            $allocations = $this->inventoryService->allocateStock(
                $request->obat_id, 
                $request->jumlah_unit
            );

            // 2. Proses Pengurangan Stok dan Pencatatan Perpetual
            $isProcessed = $this->inventoryService->processStockOut(
                $allocations, 
                $request->referensi, 
                $request->harga_jual_unit
            );
            
            if (!$isProcessed) {
                 throw new \Exception("Gagal dalam pemrosesan transaksi (DB Error).");
            }

            return redirect()->route('transaksi.keluar.create')->with('success', 'Transaksi keluar berhasil dicatat. Stok diperbarui secara perpetual.');

        } catch (\Exception $e) {
            // Tangkap exception dari allocateStock (stok kurang) atau DB error
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }
}