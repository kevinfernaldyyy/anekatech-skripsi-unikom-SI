<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_bundle', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel 'barang'
            $table->foreignId('parent_id')->constrained('barang')->onDelete('cascade');
            $table->foreignId('child_id')->constrained('barang')->onDelete('cascade');
            
            $table->integer('qty');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_bundle');
    }
};