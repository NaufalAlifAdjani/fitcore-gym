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
        Schema::table('trainer_profiles', function (Blueprint $table) {
            $table->string('tier')->nullable()->default('Senior PT Tier III');
            $table->string('studio')->nullable()->default('SCBD Studio');
            $table->decimal('rating', 3, 2)->default(4.98);
            $table->unsignedInteger('review_count')->default(184);
            $table->string('photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainer_profiles', function (Blueprint $table) {
            $table->dropColumn(['tier', 'studio', 'rating', 'review_count', 'photo_path']);
        });
    }
};
