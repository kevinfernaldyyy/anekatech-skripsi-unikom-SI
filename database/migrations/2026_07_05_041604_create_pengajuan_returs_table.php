<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_retur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->text('catatan_retur');
            $table->json('foto_kerusakan'); // Menyimpan array 5 foto sekaligus
            $table->enum('status', ['menunggu_verifikasi', 'disetujui', 'ditolak', 'barang_dikirim', 'selesai'])->default('menunggu_verifikasi');
            $table->text('pesan_admin')->nullable(); // Alasan admin jika menolak
            $table->string('resi_pengiriman_balik')->nullable(); // Diisi nanti setelah disetujui
            $table->string('foto_resi_balik')->nullable(); // Diisi nanti setelah disetujui
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_retur');
    }
};