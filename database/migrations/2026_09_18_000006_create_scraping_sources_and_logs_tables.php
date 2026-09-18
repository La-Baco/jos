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
        Schema::create('scraping_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('base_url', 255);
            $table->string('list_url', 255);
            $table->string('scraper_type', 100)->default('python_modular');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('interval_hours')->default(24);
            $table->timestamp('last_scraped_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('scraping_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scraping_source_id')->nullable()->constrained('scraping_sources')->nullOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->enum('status', ['running', 'success', 'failed'])->default('running');
            $table->unsignedInteger('jobs_found')->default(0);
            $table->unsignedInteger('jobs_new')->default(0);
            $table->unsignedInteger('jobs_updated')->default(0);
            $table->unsignedInteger('duplicates')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraping_logs');
        Schema::dropIfExists('scraping_sources');
    }
};
