<?php

namespace App\Imports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ObatsImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Pastikan kolom 'kode_obat' dan 'nama_obat' ada di file Excel
        if (!isset($row['kode_obat']) || !isset($row['nama_obat'])) {
            return null;
        }

        return new Obat([
            'kode_obat'     => $row['kode_obat'],
            'nama_obat'     => $row['nama_obat'],
            'satuan_terkecil' => 'PCS', // Nilai default, bisa disesuaikan
            'is_aktif'      => true,    // Nilai default
        ]);
    }

    /**
     * Tentukan kolom unik untuk update jika data sudah ada (upsert).
     *
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'kode_obat';
    }
}