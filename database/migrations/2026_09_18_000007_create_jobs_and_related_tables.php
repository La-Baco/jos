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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scraping_source_id')->nullable()->constrained('scraping_sources')->nullOnDelete();
            $table->text('original_url');
            $table->string('url_hash', 64)->nullable()->index();
            $table->string('content_hash', 64)->nullable()->index(); // for deduplication: hash(title + company + location)
            $table->string('title', 255);
            $table->string('normalized_title', 255)->nullable();
            $table->string('company', 255);
            $table->string('location', 255);
            $table->string('normalized_regency', 100)->nullable(); // e.g. Sumenep, Pamekasan, Surabaya
            $table->string('normalized_district', 100)->nullable(); // e.g. Kota Sumenep, Bluto, Saronggi
            $table->boolean('is_remote')->default(false);
            $table->foreignId('job_category_id')->nullable()->constrained('job_categories')->nullOnDelete();
            $table->string('employment_type', 50)->nullable()->default('Full-time'); // Full-time, Part-time, Kontrak, Freelance, Magang
            $table->foreignId('education_level_id')->nullable()->constrained('education_levels')->nullOnDelete();
            $table->unsignedSmallInteger('min_experience_years')->default(0);
            $table->unsignedSmallInteger('min_age')->nullable();
            $table->unsignedSmallInteger('max_age')->nullable();
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->string('salary_notes', 100)->nullable();
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->text('raw_skills')->nullable();
            $table->date('posted_at')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['active', 'expired', 'inactive'])->default('active');
            $table->timestamp('scraped_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'normalized_regency']);
            $table->index(['status', 'job_category_id']);
            $table->index(['status', 'education_level_id']);
        });

        Schema::create('job_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            $table->unique(['job_id', 'skill_id']);
        });

        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'job_id']);
        });

        Schema::create('job_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['job_id', 'viewed_at']);
        });

        Schema::create('job_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->unsignedTinyInteger('total_score')->default(0); // 0 - 100
            $table->unsignedTinyInteger('skill_score')->default(0);
            $table->unsignedTinyInteger('education_score')->default(0);
            $table->unsignedTinyInteger('location_score')->default(0);
            $table->unsignedTinyInteger('experience_score')->default(0);
            $table->unsignedTinyInteger('interest_score')->default(0);
            $table->unsignedTinyInteger('age_score')->default(0);
            $table->json('score_breakdown')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->unique(['user_id', 'job_id']);
            $table->index(['user_id', 'total_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_recommendations');
        Schema::dropIfExists('job_views');
        Schema::dropIfExists('saved_jobs');
        Schema::dropIfExists('job_skills');
        Schema::dropIfExists('jobs');
    }
};
