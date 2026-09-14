<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            // Kita kasih default 1 supaya data transaksi lama tetap aman (dianggap Unit #1)
            $table->unsignedInteger('nomor_unit')->default(1)->after('transaksi_id');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->dropColumn('nomor_unit');
        });
    }
};
