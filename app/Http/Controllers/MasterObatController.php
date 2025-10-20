<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class MasterObatController extends Controller
{
    /**
     * Menampilkan daftar Master Obat.
     */
    public function index()
    {
        $obats = Obat::with('batches') 
                      ->orderBy('nama_obat')
                      ->paginate(10);
        return view('master.obat.index', compact('obats'));
    }

    /**
     * Menampilkan form untuk membuat Obat baru.
     */
    public function create()
    {
        return view('master.obat.create');
    }

    /**
     * Menyimpan Obat baru ke database. (STORE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_obat' => 'required|string|max:50|unique:obat,kode_obat',
            'nama_obat' => 'required|string|max:255',
            'satuan_terkecil' => 'required|string|max:50',
            'is_aktif' => 'required|boolean',
        ]);

        Obat::create($request->all());

        return redirect()->route('master-obat.index')->with('success', 'Data obat berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan detail obat (Opsional).
     */
    public function show(Obat $masterObat)
    {
        // Untuk saat ini, kita bisa langsung mengarahkan ke halaman edit atau tidak perlu diimplementasikan.
        return redirect()->route('master-obat.edit', $masterObat);
    }
    
    /**
     * Menampilkan form untuk mengedit Obat. (EDIT)
     */
    public function edit(Obat $masterObat)
    {
        // Nama parameter route default dari resource adalah 'master_obat', jadi gunakan $masterObat
        return view('master.obat.edit', ['obat' => $masterObat]);
    }

    /**
     * Memperbarui Obat di database. (UPDATE)
     */
    public function update(Request $request, Obat $masterObat)
    {
        $request->validate([
            // Unique rule: abaikan ID obat yang sedang diupdate
            'kode_obat' => 'required|string|max:50|unique:obat,kode_obat,' . $masterObat->id,
            'nama_obat' => 'required|string|max:255',
            'satuan_terkecil' => 'required|string|max:50',
            'is_aktif' => 'required|boolean',
        ]);

        $masterObat->update($request->all());

        return redirect()->route('master-obat.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    /**
     * Menghapus Obat dari database. (DESTROY)
     */
    public function destroy(Obat $masterObat)
    {
        // Peringatan: Hapus obat akan gagal jika sudah ada relasi di tabel batch_obat (onDelete('restrict')).
        // Ini bagus untuk menjaga integritas data perpetual.
        try {
            $masterObat->delete();
            return redirect()->route('master-obat.index')->with('success', 'Obat berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('master-obat.index')->with('error', 'Gagal menghapus obat karena sudah ada data transaksi terkait.');
        }
    }
}