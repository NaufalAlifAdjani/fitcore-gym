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
        // 1. Drop min_membership_days from pt_packages
        Schema::table('pt_packages', function (Blueprint $table) {
            if (Schema::hasColumn('pt_packages', 'min_membership_days')) {
                $table->dropColumn('min_membership_days');
            }
        });

        // 2. Drop notes, payout_status, payout_date, gym_revenue from pt_sessions
        Schema::table('pt_sessions', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['notes', 'payout_status', 'payout_date', 'gym_revenue'] as $col) {
                if (Schema::hasColumn('pt_sessions', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // 3. Drop payout_status, payout_date from payments
        Schema::table('payments', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['payout_status', 'payout_date'] as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        // 4. Drop checked_out_at from check_ins
        Schema::table('check_ins', function (Blueprint $table) {
            if (Schema::hasColumn('check_ins', 'checked_out_at')) {
                $table->dropColumn('checked_out_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pt_packages', function (Blueprint $table) {
            $table->unsignedInteger('min_membership_days')->default(0);
        });

        Schema::table('pt_sessions', function (Blueprint $table) {
            $table->text('notes')->nullable();
            $table->string('payout_status')->default('pending');
            $table->timestamp('payout_date')->nullable();
            $table->decimal('gym_revenue', 12, 2)->nullable()->default(0);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payout_status')->default('pending');
            $table->timestamp('payout_date')->nullable();
        });

        Schema::table('check_ins', function (Blueprint $table) {
            $table->dateTime('checked_out_at')->nullable();
        });
    }
};
