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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->string('id', 128)->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at');
            $table->string('device_id', 255)->nullable();
            $table->string('refresh_token', 255)->nullable();
            $table->timestamp('last_activity')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index('user_id', 'idx_sessions_user_id');
            $table->index('expires_at', 'idx_sessions_expires');
            $table->index('device_id', 'idx_device_id');
            $table->index('refresh_token', 'idx_refresh_token');
            $table->index('last_activity', 'idx_last_activity');
            $table->index(['user_id', 'device_id'], 'idx_user_device');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
