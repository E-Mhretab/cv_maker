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
        Schema::create('cv', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('address', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 100);
            $table->date('date_of_birth')->nullable();
            $table->string('linkedin_profile', 255)->nullable();
            $table->string('portfolio', 255)->nullable();
            $table->text('profile_summary')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Indexes
            $table->index('user_id', 'idx_cv_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv');
    }
};
