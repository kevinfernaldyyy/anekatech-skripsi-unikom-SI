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
    Schema::create('transaksi', function (Blueprint $table) {
        $table->id();
        $table->string('no_transaksi')->unique();
        $table->foreignId('user_id')->constrained('users');
        
        // --- INI KOLOM YANG PENTING, PASTIKAN ADA ---
        $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
        $table->string('nama_customer'); // Tetap simpan nama sebagai snapshot
        $table->string('nomor_telepon')->nullable();
        $table->text('alamat')->nullable();
        
        $table->enum('metode_pengiriman', ['pickup', 'delivery', 'courier'])->default('pickup');
        $table->decimal('biaya_pengiriman', 15, 2)->default(0); // Ongkir
        
        $table->enum('status_pembayaran', ['lunas', 'dp'])->default('lunas');
        
        $table->decimal('total_harga', 15, 2);
        $table->decimal('bayar', 15, 2);
        $table->decimal('kembalian', 15, 2)->default(0);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
