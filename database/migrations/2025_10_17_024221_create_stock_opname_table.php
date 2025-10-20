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
        Schema::create('stock_opname', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batch_obat')->onDelete('restrict');
            $table->date('tanggal_opname');
            $table->integer('stok_tercatat_sistem');
            $table->integer('stok_fisik');
            $table->integer('selisih'); // Stok Fisik - Stok Sistem
            $table->decimal('nilai_selisih', 15, 2); // Selisih * Harga Beli
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname');
    }
};