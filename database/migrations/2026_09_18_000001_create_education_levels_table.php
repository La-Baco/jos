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
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // SD, SMP, SMA/SMK, D1, D2, D3, D4, S1, S2, S3
            $table->string('code', 20)->unique();
            $table->unsignedTinyInteger('hierarchy_order')->default(1); // 1 for SD up to 10 for S3
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_levels');
    }
};
