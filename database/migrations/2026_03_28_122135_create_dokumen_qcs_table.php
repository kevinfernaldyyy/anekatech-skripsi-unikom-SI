<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_qcs', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel transaksi
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            
            // Kolom untuk menyimpan path foto yang di-upload teknisi
            $table->string('foto_depan')->nullable();
            $table->string('foto_belakang')->nullable();
            $table->string('foto_menyala')->nullable();
            
            // Catatan tambahan dari teknisi jika ada kelainan
            $table->text('catatan_teknisi')->nullable();
            
            // Nama file PDF akhir yang di-generate sistem
            $table->string('file_pdf')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_qcs');
    }
};