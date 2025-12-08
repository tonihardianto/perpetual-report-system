<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_opname_headers', function (Blueprint $table) {
            $table->id();
            // Periode SO
            $table->unsignedTinyInteger('bulan')->comment('Bulan Stock Opname (1-12)');
            $table->year('tahun')->comment('Tahun Stock Opname');
            
            // Kolom Status
            $table->string('status', 50)->default('Selesai')->comment('Status SO: Selesai, Draft, dll.');
            
            // Pencatatan waktu
            $table->timestamp('tanggal_so_dilakukan')->nullable()->comment('Tanggal SO ini dicatat (bukan tanggal periode SO)');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_opname_headers');
    }
};