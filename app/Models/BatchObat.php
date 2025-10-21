<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchObat extends Model
{
    use HasFactory;
    
    protected $table = 'batch_obat';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_ed' => 'date',
    ];

    /**
     * Relasi ke model Obat
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    /**
     * Relasi ke model TransaksiMutasi
     */
    public function transaksiMutasi()
    {
        return $this->hasMany(TransaksiMutasi::class, 'batch_id');
    }
}