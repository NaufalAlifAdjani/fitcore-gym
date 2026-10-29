<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pt_sessions', function (Blueprint $table) {
            $table->index(['trainer_id', 'session_date', 'start_time'], 'pt_sessions_trainer_slot_idx');
            $table->index(['member_id', 'session_date'], 'pt_sessions_member_date_idx');
        });

        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['pgsql', 'sqlite'])) {
            if ($driver === 'pgsql') {
                DB::statement("
                    UPDATE pt_sessions
                    SET status = 'cancelled'
                    WHERE id IN (
                        SELECT id FROM (
                            SELECT id, ROW_NUMBER() OVER (
                                PARTITION BY trainer_id, session_date, start_time 
                                ORDER BY id ASC
                            ) as rnum
                            FROM pt_sessions
                            WHERE status != 'cancelled'
                        ) t
                        WHERE t.rnum > 1
                    )
                ");
            }

            DB::statement("
                CREATE UNIQUE INDEX pt_sessions_trainer_date_time_unique 
                ON pt_sessions (trainer_id, session_date, start_time) 
                WHERE status != 'cancelled'
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['pgsql', 'sqlite'])) {
            DB::statement('DROP INDEX IF EXISTS pt_sessions_trainer_date_time_unique');
        }

        Schema::table('pt_sessions', function (Blueprint $table) {
            $table->dropIndex('pt_sessions_trainer_slot_idx');
            $table->dropIndex('pt_sessions_member_date_idx');
        });
    }
};
