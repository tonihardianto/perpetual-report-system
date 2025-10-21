<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMutasi;
use App\Models\Obat;
use Illuminate\Http\Request;

class TransaksiMutasiController extends Controller
{
    /**
     * Menampilkan daftar semua Transaksi Mutasi (Masuk, Keluar, Penyesuaian).
     */
    public function index(Request $request)
    {
        $obats = Obat::orderBy('nama_obat')->get();
        
        $query = TransaksiMutasi::with(['batch.obat'])
            ->orderBy('tanggal_transaksi', 'desc');

        // Filter berdasarkan Tipe Transaksi
        if ($request->filled('tipe_transaksi')) {
            $query->where('tipe_transaksi', $request->tipe_transaksi);
        }

        // Filter berdasarkan Obat
        if ($request->filled('obat_id')) {
            $obatId = $request->obat_id;
            // Gunakan whereHas karena batch_id yang berelasi ke obat
            $query->whereHas('batch', function($q) use ($obatId) {
                $q->where('obat_id', $obatId);
            });
        }
        
        // Filter berdasarkan Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }

        $mutasi = $query->paginate(10)->withQueryString();

        // Data untuk dropdown filter
        $tipe_transaksi_list = TransaksiMutasi::select('tipe_transaksi')->distinct()->pluck('tipe_transaksi');

        return view('transaksi.mutasi.index', compact(
            'mutasi', 
            'obats', 
            'tipe_transaksi_list',
            'request'
        ));
    }
}