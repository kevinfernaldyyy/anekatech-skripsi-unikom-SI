<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
    {
        // Pastikan nama tabelnya sesuai dengan error, yaitu 'transaksi_details'
        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->text('link')->nullable()->after('harga_satuan'); // <--- UBAH DI SINI
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            //
        });
    }
};
