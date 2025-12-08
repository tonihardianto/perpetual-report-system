<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    // Nama tabel disesuaikan dengan migration Anda
    protected $table = 'stock_opname'; 
    
    // Lindungi kolom yang tidak boleh diisi secara massal (misalnya 'id')
    protected $guarded = ['id']; 
    
    /**
     * The attributes that should be cast.
     */
    protected $casts = ['tanggal_opname' => 'date'];

    /**
     * Relasi ke Batch Obat.
     */
    public function batch()
    {
        return $this->belongsTo(BatchObat::class, 'batch_id');
    }
}