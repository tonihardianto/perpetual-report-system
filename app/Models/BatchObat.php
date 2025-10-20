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

    // public function obat()
    // {
    //     return $this->belongsTo(Obat::class);
    // }

    public function mutasi()
    {
        return $this->hasMany(TransaksiMutasi::class);
    }
    // app/Models/BatchObat.php

// ...

public function obat()
{
    // Relasi One-to-One/Many ke model Obat
    return $this->belongsTo(\App\Models\Obat::class, 'obat_id');
}

public function transaksiMutasi()
{
    // Relasi One-to-Many ke model TransaksiMutasi
    return $this->hasMany(\App\Models\TransaksiMutasi::class, 'batch_id');
}
}