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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->string('language_name', 100);
            $table->enum('proficiency', ['basic', 'conversational', 'fluent', 'native']);
            
            // Indexes
            $table->index('cv_id', 'idx_languages_cv_id');
            $table->index('language_name', 'idx_languages_name');
            $table->index('proficiency', 'idx_languages_proficiency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
