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
            $table->unsignedInteger('commission_rate')->default(60000)->after('experience');
        });

        Schema::table('pt_sessions', function (Blueprint $table) {
            $table->unsignedInteger('trainer_earnings')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pt_sessions', function (Blueprint $table) {
            $table->dropColumn('trainer_earnings');
        });

        Schema::table('trainer_profiles', function (Blueprint $table) {
            $table->dropColumn('commission_rate');
        });
    }
};
