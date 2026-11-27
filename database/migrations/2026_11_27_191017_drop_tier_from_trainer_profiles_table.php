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
            if (Schema::hasColumn('trainer_profiles', 'tier')) {
                $table->dropColumn('tier');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainer_profiles', function (Blueprint $table) {
            $table->string('tier')->nullable()->default('Senior PT Tier III');
        });
    }
};
