<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('transaksi', function (Blueprint $table) {
        $table->string('foto_paket_ekspedisi')->nullable()->after('foto_resi_pengiriman');
    });
}

public function down()
{
    Schema::table('transaksi', function (Blueprint $table) {
        $table->dropColumn('foto_paket_ekspedisi');
    });
}
};
