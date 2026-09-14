<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('transaksi', function (Blueprint $table) {
        // $table->boolean('is_rakit')->default(0);  <-- INI DIHAPUS AJA
        $table->foreignId('teknisi_id')->nullable()->constrained('users')->nullOnDelete(); // (Contoh kalau ada kolom teknisi, biarin aja)
    });
}

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['teknisi_id']);
            $table->dropColumn(['is_rakit', 'teknisi_id']);
        });
    }
};
