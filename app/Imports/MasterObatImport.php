<?php

namespace App\Imports;

use App\Models\MasterObat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterObatImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['kode_obat']) || !isset($row['nama_obat'])) {
            return null; // lewati baris invalid
        }

        return new MasterObat([
            'kode_obat' => $row['kode_obat'],
            'nama_obat' => $row['nama_obat'],
            'satuan'    => $row['satuan'] ?? null,
            'stok_minimum' => $row['stok_minimum'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
