<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds a unique qr_token to member_profiles for QR Code check-in.
     * Backfills existing rows with a generated token safely.
     */
    public function up(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            // Add as nullable first so existing rows don't violate NOT NULL
            $table->string('qr_token')->nullable()->unique()->after('member_code');
        });

        // Backfill: assign a unique token to every existing member_profile
        DB::table('member_profiles')
            ->whereNull('qr_token')
            ->orderBy('id')
            ->each(function ($row) {
                DB::table('member_profiles')
                    ->where('id', $row->id)
                    ->update(['qr_token' => Str::uuid()->toString()]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_profiles', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn('qr_token');
        });
    }
};
