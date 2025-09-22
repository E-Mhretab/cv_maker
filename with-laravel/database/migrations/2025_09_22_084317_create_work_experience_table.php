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
        Schema::create('work_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->string('job_title', 100);
            $table->string('company_name', 150);
            $table->date('work_start')->nullable();
            $table->date('work_end')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_current')->default(false);
            
            // Indexes
            $table->index('cv_id', 'idx_work_experience_cv_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experience');
    }
};
