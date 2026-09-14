<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dokumen_qcs', function (Blueprint $table) {
            // Kolom penanda urutan unit PC & label namanya
            $table->integer('nomor_unit')->default(1)->after('transaksi_id');
            $table->string('nama_pc')->nullable()->after('nomor_unit');
        });
    }

    public function down()
    {
        Schema::table('dokumen_qcs', function (Blueprint $table) {
            $table->dropColumn(['nomor_unit', 'nama_pc']);
        });
    }
};
