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
    Schema::table('pengajuan_return', function (Blueprint $table) {
        $table->integer('nominal_refund')->nullable()->after('bukti_refund');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_return', function (Blueprint $table) {
            //
        });
    }
};
