<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    
    // Sesuaikan nama tabel jika Anda menggunakan snake_case plural (e.g., obats)
    protected $table = 'obat'; 
    protected $guarded = ['id'];

    public function batches()
    {
        return $this->hasMany(BatchObat::class);
    }
    public function getTotalStockAttribute()
    {
        // Hitung total sisa_stok dari semua batch yang memiliki stok > 0
        return $this->batches()->sum('sisa_stok');
    }
}