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
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->string('degree', 150);
            $table->string('institution', 150);
            $table->date('education_start')->nullable();
            $table->date('education_end')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            
            // Indexes
            $table->index('cv_id', 'idx_education_cv_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
