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
        Schema::create('cv_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->smallInteger('template_type')->default(1);
            $table->boolean('is_public')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->unique('cv_id', 'unique_cv_metadata');
            $table->index('template_type', 'idx_cv_metadata_template_type');
            $table->index('is_public', 'idx_cv_metadata_is_public');
            $table->index('published_at', 'idx_cv_metadata_published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_metadata');
    }
};
