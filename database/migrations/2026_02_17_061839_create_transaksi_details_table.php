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
    Schema::create('transaksi_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
        $table->foreignId('barang_id')->constrained('barang'); // Barang apa yg dibeli
        $table->integer('qty');
        $table->decimal('harga_satuan', 15, 2); // Harga saat transaksi terjadi (PENTING! Jangan ambil dr master barang, krn harga bisa berubah nanti)
        $table->decimal('subtotal', 15, 2); // qty * harga
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
