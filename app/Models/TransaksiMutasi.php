<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMutasi extends Model
{
    use HasFactory;

    protected $table = 'transaksi_mutasi';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'tipe_transaksi' => 'string',
    ];

    public function batch()
    {
        return $this->belongsTo(BatchObat::class);
    }
}