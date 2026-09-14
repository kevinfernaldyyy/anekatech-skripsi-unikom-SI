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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            
            // --- FITUR STATUS & ROLE ---
            $table->enum('role', ['admin', 'teknisi', 'pelanggan'])->default('pelanggan');
            $table->enum('account_status', ['active', 'inactive'])->default('active');
            
            // --- FITUR ONLINE TRACKING ---
            $table->boolean('is_online')->default(false); // TRUE jika sedang login
            $table->timestamp('last_login_at')->nullable(); // Jam terakhir login
            $table->timestamp('last_logout_at')->nullable(); // Jam terakhir logout
            
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
