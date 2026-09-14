<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Tambah 2 kolom baru tepat di bawah kolom is_rakit
            $table->string('estimasi_po')->nullable()->after('is_rakit');
            $table->text('catatan')->nullable()->after('estimasi_po');
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Hapus kolom kalau sewaktu-waktu di-rollback
            $table->dropColumn(['estimasi_po', 'catatan']);
        });
    }
};
