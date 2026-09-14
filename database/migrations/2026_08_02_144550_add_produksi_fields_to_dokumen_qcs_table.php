<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_qcs', function (Blueprint $table) {
            $table->unsignedBigInteger('transaksi_id')->nullable()->change();
            $table->foreignId('barang_id')->nullable()->after('transaksi_id')->constrained('barang')->nullOnDelete();
            $table->foreignId('rakitan_log_id')->nullable()->after('barang_id')->constrained('rakitan_logs')->cascadeOnDelete();            
            $table->enum('jenis_qc', ['transaksi', 'produksi'])->default('transaksi')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_qcs', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->dropForeign(['rakitan_log_id']);
            $table->dropColumn(['barang_id', 'rakitan_log_id', 'jenis_qc']);
        });
    }
};