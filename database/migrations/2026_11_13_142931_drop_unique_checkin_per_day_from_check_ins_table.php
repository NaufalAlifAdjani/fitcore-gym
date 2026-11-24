<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drops the unique(member_id, check_in_date) constraint to allow
     * multiple check-ins per day (controlled by 10-minute cooldown instead).
     */
    public function up(): void
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropUnique('unique_checkin_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->unique(['member_id', 'check_in_date'], 'unique_checkin_per_day');
        });
    }
};
