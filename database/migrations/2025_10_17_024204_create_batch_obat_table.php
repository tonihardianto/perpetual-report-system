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
        Schema::create('batch_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('obat')->onDelete('cascade');
            $table->string('nomor_batch', 100);
            $table->date('tanggal_masuk');
            $table->date('tanggal_ed'); // Tanggal Kedaluwarsa
            $table->decimal('harga_beli_per_satuan', 15, 2); // Kunci HPP
            $table->integer('stok_awal')->default(0); // Stok awal saat batch diterima
            $table->integer('sisa_stok')->default(0); // Stok saat ini (Real-time)

            // Kombinasi untuk memastikan satu obat tidak memiliki nomor batch yang sama
            $table->unique(['obat_id', 'nomor_batch']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_obat');
    }
};