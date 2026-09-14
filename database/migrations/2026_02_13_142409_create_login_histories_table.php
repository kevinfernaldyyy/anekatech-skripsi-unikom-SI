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
    Schema::create('login_histories', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('ip_address', 45)->nullable();
        $table->string('user_agent')->nullable(); // Device yang dipakai (HP/Laptop)
        $table->timestamp('login_at')->useCurrent();
        $table->timestamp('logout_at')->nullable();
        $table->integer('duration_minutes')->nullable(); // Durasi kerja/online
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
