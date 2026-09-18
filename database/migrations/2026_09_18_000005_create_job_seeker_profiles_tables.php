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
        Schema::create('job_seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('phone', 30)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('province', 100)->default('Jawa Timur');
            $table->string('regency', 100)->default('Sumenep'); // Kabupaten
            $table->string('district', 100)->nullable(); // Kecamatan
            $table->string('village', 100)->nullable(); // Desa / Kelurahan
            $table->foreignId('education_level_id')->nullable()->constrained('education_levels')->nullOnDelete();
            $table->string('major', 150)->nullable(); // Jurusan
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->boolean('willing_outside_sumenep')->default(true);
            $table->boolean('willing_outside_madura')->default(false);
            $table->boolean('willing_remote')->default(true);
            $table->decimal('expected_salary_min', 12, 2)->nullable();
            $table->string('preferred_location', 150)->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->enum('proficiency_level', ['pemula', 'menengah', 'ahli'])->default('menengah');
            $table->timestamps();

            $table->unique(['user_id', 'skill_id']);
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('job_title', 150);
            $table->string('company_name', 150);
            $table->unsignedSmallInteger('start_year');
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('user_job_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('job_category_id')->constrained('job_categories')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'job_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_job_preferences');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('user_skills');
        Schema::dropIfExists('job_seeker_profiles');
    }
};
