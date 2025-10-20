<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_mutasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batch_obat')->onDelete('restrict');
            $table->dateTime('tanggal_transaksi');
            
            // TIPE: MASUK (Beli), KELUAR (Jual/Pakai), PENYESUAIAN
            $table->enum('tipe_transaksi', ['MASUK', 'KELUAR', 'PENYESUAIAN']); 
            
            $table->integer('jumlah_unit'); // Kuantitas (Selalu positif, tipe_transaksi menentukan arah)
            
            // Data Keuangan (Penting untuk HPP dan Laporan Perpetual)
            $table->decimal('harga_pokok_unit', 15, 2); // Harga Beli (HPP)
            $table->decimal('total_hpp', 15, 2); // Jumlah Unit * Harga Pokok
            $table->decimal('harga_jual_unit', 15, 2)->nullable(); // Diisi hanya saat KELUAR
            
            $table->string('referensi', 100)->nullable(); // No. Faktur Beli/Jual/SO
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_mutasi');
    }
};