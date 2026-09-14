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
    Schema::table('transaksi', function (Blueprint $table) {
        // Tambahkan kolom baru setelah kolom bukti_pelunasan (atau terserah)
        $table->text('pesan_penolakan')->nullable()->after('bukti_pelunasan');
    });
}

public function down()
{
    Schema::table('transaksi', function (Blueprint $table) {
        // Buat ngehapus kolom kalau sewaktu-waktu di-rollback
        $table->dropColumn('pesan_penolakan');
    });
}
};
