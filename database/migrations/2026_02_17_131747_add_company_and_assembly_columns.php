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
    Schema::table('customers', function (Blueprint $table) {
        $table->string('perusahaan')->nullable()->after('nama'); // Nama PT/CV
    });

    Schema::table('transaksi', function (Blueprint $table) {
        $table->boolean('is_rakit')->default(false)->after('biaya_pengiriman'); // Status minta dirakit
    });
}

public function down(): void
{
    Schema::table('customers', fn($t) => $t->dropColumn('perusahaan'));
    Schema::table('transaksi', fn($t) => $t->dropColumn('is_rakit'));
}
};
