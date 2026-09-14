<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengiriman_teknisi', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel transaksi
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            
            // Rekam jejak waktu (Timestamp)
            $table->timestamp('waktu_berangkat')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            
            // Bukti Fisik di Lapangan
            $table->string('foto_bukti_instalasi')->nullable(); 
            $table->string('foto_kartu_garansi')->nullable();
            
            // Catatan tambahan (opsional jika teknisi ingin melapor sesuatu)
            $table->text('catatan_lapangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengiriman_teknisi');
    }
};