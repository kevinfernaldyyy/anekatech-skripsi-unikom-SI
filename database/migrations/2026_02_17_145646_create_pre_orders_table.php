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
    Schema::create('pre_orders', function (Blueprint $table) {
        $table->id();
        $table->string('no_po')->unique(); // PO-20260217-XXXX
        
        // Terhubung ke Transaksi (Sumber Uang/DP-nya)
        $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
        
        // Terhubung ke Customer
        $table->foreignId('customer_id')->constrained('customers');
        
        $table->date('estimasi_ready')->nullable();
        
        // Status khusus PO
        // 'pending' = Menunggu Barang Datang
        // 'arrived' = Barang Sudah di Toko (Siap Ambil)
        // 'completed' = Sudah Diambil Customer
        $table->enum('status', ['pending', 'arrived', 'completed', 'cancel'])->default('pending');
        
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_orders');
    }
};
