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
    Schema::create('email_logs', function (Blueprint $table) {
        $table->id();
        // Terhubung ke Customer & PO mana (opsional, biar gampang tracking)
        $table->foreignId('customer_id')->nullable()->constrained('customers');
        $table->foreignId('pre_order_id')->nullable()->constrained('pre_orders');
        
        $table->string('recipient_email'); // Email tujuan
        $table->string('subject');         // Judul Email
        $table->text('body');              // Isi Pesan (HTML)
        
        // Status pengiriman: 'pending' (belum), 'sent' (sukses), 'failed' (gagal)
        $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
        
        // Tipe notifikasi: 'invoice_po', 'barang_ready', 'payment_reminder'
        $table->string('trigger_type')->default('general'); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
