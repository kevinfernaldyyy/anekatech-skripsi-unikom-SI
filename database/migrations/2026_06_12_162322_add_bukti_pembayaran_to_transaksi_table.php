<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Menambahkan kolom bukti_pembayaran setelah kolom status_pembayaran
            $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });
    }
};
