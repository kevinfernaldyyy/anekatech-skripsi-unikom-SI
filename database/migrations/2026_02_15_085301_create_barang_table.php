<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique(); 
            $table->string('nama_barang'); 
            $table->enum('jenis', ['komponen', 'paket'])->default('komponen');
            $table->string('socket')->nullable()->comment('Contoh: AM4, LGA1700');
            $table->string('tipe_memory')->nullable()->comment('Contoh: DDR4, DDR5');
            $table->string('form_factor')->nullable()->comment('ATX, mATX, ITX');
            $table->integer('power_watt')->default(0)->comment('Kebutuhan daya (CPU/VGA) atau Kapasitas (PSU)');
            $table->string('kategori');
            $table->decimal('harga_jual', 15, 2); 
            $table->integer('stok')->default(0);
            
            $table->string('satuan')->default('Unit'); 
            $table->string('gambar')->nullable(); 
            $table->text('deskripsi')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // PERBAIKAN: Nama tabel harus 'barang' (bukan barangs)
        Schema::dropIfExists('barang');
    }
};