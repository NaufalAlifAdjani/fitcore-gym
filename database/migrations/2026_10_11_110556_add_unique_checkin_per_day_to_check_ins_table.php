<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 1. Adds check_in_date (date) — used for the per-day unique constraint.
     * 2. Adds checked_by — FK to the admin/petugas who scanned the QR.
     * 3. Backfills check_in_date from existing checked_in_at values.
     * 4. Deduplicates existing rows (seeder may have created multiple per day).
     * 5. Adds the unique(member_id, check_in_date) constraint.
     */
    public function up(): void
    {
        // Step 1 & 2: Add columns
        Schema::table('check_ins', function (Blueprint $table) {
            $table->date('check_in_date')->nullable()->after('membership_id');
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete()->after('method');
        });

        // Step 3: Backfill check_in_date from checked_in_at (Asia/Jakarta timezone)
        DB::table('check_ins')
            ->whereNull('check_in_date')
            ->orderBy('id')
            ->each(function ($row) {
                $date = $row->checked_in_at
                    ? Carbon::parse($row->checked_in_at)->timezone('Asia/Jakarta')->toDateString()
                    : now('Asia/Jakarta')->toDateString();

                DB::table('check_ins')
                    ->where('id', $row->id)
                    ->update(['check_in_date' => $date]);
            });

        // Step 4: Deduplicate — keep only the earliest check-in per (member_id, check_in_date).
        // The seeder may have created multiple check-ins on the same date; we remove extras
        // so the unique constraint can be added without affecting unrelated data.
        $duplicates = DB::table('check_ins')
            ->select('member_id', 'check_in_date', DB::raw('MIN(id) as keep_id'))
            ->groupBy('member_id', 'check_in_date')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            DB::table('check_ins')
                ->where('member_id', $dup->member_id)
                ->where('check_in_date', $dup->check_in_date)
                ->where('id', '!=', $dup->keep_id)
                ->delete();
        }

        // Step 5: Add unique constraint
        Schema::table('check_ins', function (Blueprint $table) {
            $table->unique(['member_id', 'check_in_date'], 'unique_checkin_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropUnique('unique_checkin_per_day');
            $table->dropForeign(['checked_by']);
            $table->dropColumn(['check_in_date', 'checked_by']);
        });
    }
};
