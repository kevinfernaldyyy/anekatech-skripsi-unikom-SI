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
    Schema::create('pre_order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pre_order_id')->constrained('pre_orders')->onDelete('cascade');
        
        // Barang apa yg di-PO-kan
        $table->foreignId('barang_id')->constrained('barang');
        
        $table->integer('qty');
        $table->decimal('harga_satuan', 15, 2);
        $table->decimal('subtotal', 15, 2);
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_order_items');
    }
};
