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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('trainer_id')->nullable()->after('member_id')->constrained('users')->nullOnDelete();
            $table->decimal('gym_revenue', 12, 2)->default(0)->after('amount');
            $table->decimal('trainer_revenue', 12, 2)->default(0)->after('gym_revenue');
            $table->string('payout_status')->default('pending')->after('trainer_revenue');
            $table->timestamp('payout_date')->nullable()->after('payout_status');
        });

        Schema::table('pt_sessions', function (Blueprint $table) {
            $table->decimal('gym_revenue', 12, 2)->nullable()->default(0)->after('status');
            $table->decimal('trainer_revenue', 12, 2)->nullable()->default(0)->after('gym_revenue');
            $table->string('payout_status')->default('pending')->after('trainer_revenue');
            $table->timestamp('payout_date')->nullable()->after('payout_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pt_sessions', function (Blueprint $table) {
            $table->dropColumn(['gym_revenue', 'trainer_revenue', 'payout_status', 'payout_date']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('trainer_id');
            $table->dropColumn(['gym_revenue', 'trainer_revenue', 'payout_status', 'payout_date']);
        });
    }
};
