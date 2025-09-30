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
        // CV main table - personal information
        Schema::create('cv', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email');
            $table->date('date_of_birth')->nullable();
            $table->string('linkedin_profile')->nullable();
            $table->string('portfolio')->nullable();
            $table->text('profile_summary')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // CV metadata table - template and publication info
        Schema::create('cv_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->tinyInteger('template_type')->default(1); // 1=nathan, 2=esey, 3=mirian
            $table->boolean('is_public')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Skills table
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->string('skill_name');
            $table->text('description')->nullable();
        });

        // Languages table
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->string('language_name');
            $table->enum('proficiency', ['basic', 'conversational', 'fluent', 'native']);
        });

        // Work experience table
        Schema::create('work_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->string('company_name');
            $table->string('position');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        // Education table
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cv')->onDelete('cascade');
            $table->string('institution_name');
            $table->string('degree');
            $table->string('field_of_study')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

        // User sessions table for enhanced session management
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('device_id')->nullable();
            $table->string('refresh_token')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('refresh_expires_at')->nullable();
            $table->timestamp('last_activity')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();

            $table->index(['user_id', 'device_id']);
            $table->index('expires_at');
            $table->index('last_activity');
        });

        // Audit logs table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action', 50);
            $table->string('table_name', 50);
            $table->unsignedBigInteger('record_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('timestamp')->useCurrent();

            $table->index(['user_id', 'action']);
            $table->index(['table_name', 'record_id']);
            $table->index('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('user_sessions');
        Schema::dropIfExists('education');
        Schema::dropIfExists('work_experience');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('cv_metadata');
        Schema::dropIfExists('cv');
    }
};