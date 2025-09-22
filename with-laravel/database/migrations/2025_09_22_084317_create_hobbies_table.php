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
        Schema::create('hobbies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->string('hobby_name', 100);
            $table->text('description')->nullable();
            
            // Indexes
            $table->index('cv_id', 'idx_hobbies_cv_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hobbies');
    }
};
