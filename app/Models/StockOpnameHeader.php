<?php

// app/Models/StockOpnameHeader.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameHeader extends Model
{
    // Tambahkan semua kolom yang diizinkan untuk diisi secara massal di sini
    protected $guarded = ['id'];
    /* protected $fillable = [
        'bulan',
        'tahun',
        'status',
        'tanggal_so_dilakukan',
    ]; */
    
    // Optional: Jika Anda ingin memformat tanggal
    protected $casts = [
        'tanggal_so_dilakukan' => 'datetime',
    ];
}