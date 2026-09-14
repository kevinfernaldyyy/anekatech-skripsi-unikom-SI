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
        Schema::table('transaksi', function (Blueprint $table) {
            // Kolom untuk Pengiriman Awal (Admin ke Kustomer)
            $table->string('resi_pengiriman')->nullable()->after('metode_pengiriman');
            $table->string('foto_resi_pengiriman')->nullable()->after('resi_pengiriman');
            
            // Kolom untuk Skenario Retur (Kustomer ke Admin)
            $table->string('resi_retur')->nullable()->after('foto_resi_pengiriman');
            $table->string('foto_resi_retur')->nullable()->after('resi_retur');
            $table->text('catatan_retur')->nullable()->after('foto_resi_retur');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'resi_pengiriman', 
                'foto_resi_pengiriman', 
                'resi_retur', 
                'foto_resi_retur', 
                'catatan_retur'
            ]);
        });
    }
};