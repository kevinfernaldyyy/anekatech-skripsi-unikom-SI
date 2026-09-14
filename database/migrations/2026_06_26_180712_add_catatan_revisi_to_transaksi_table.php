<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Menambahkan kolom catatan_revisi bertipe text, boleh kosong (nullable)
            // dan ditaruh tepat setelah kolom catatan lama biar rapi di database.
            $table->text('catatan_revisi')->nullable()->after('catatan');
        });
    }
    
    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('catatan_revisi');
        });
    }
};
