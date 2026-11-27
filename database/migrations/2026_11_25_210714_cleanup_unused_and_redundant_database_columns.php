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
        // 1. Drop unused table ratings
        Schema::dropIfExists('ratings');

        // 2. Drop obsolete qr_token column on member_profiles
        if (Schema::hasColumn('member_profiles', 'qr_token')) {
            Schema::table('member_profiles', function (Blueprint $table) {
                if (DB::getDriverName() === 'sqlite') {
                    $table->dropUnique(['qr_token']);
                }
                $table->dropColumn('qr_token');
            });
        }

        // 3. Drop unused/marketing columns on trainer_profiles (Option B)
        Schema::table('trainer_profiles', function (Blueprint $table) {
            $cols = [];
            foreach (['studio', 'rating', 'review_count', 'photo_path'] as $col) {
                if (Schema::hasColumn('trainer_profiles', $col)) {
                    $cols[] = $col;
                }
            }
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        // 4. Drop duplicated/synchronized columns on membership_packages
        Schema::table('membership_packages', function (Blueprint $table) {
            $cols = [];
            foreach (['duration_in_days', 'pt_sessions', 'status'] as $col) {
                if (Schema::hasColumn('membership_packages', $col)) {
                    $cols[] = $col;
                }
            }
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_packages', function (Blueprint $table) {
            $table->unsignedInteger('duration_in_days')->nullable();
            $table->unsignedInteger('pt_sessions')->default(0);
            $table->string('status')->default('active');
        });

        Schema::table('trainer_profiles', function (Blueprint $table) {
            $table->string('studio')->nullable();
            $table->decimal('rating', 3, 2)->default(4.98);
            $table->unsignedInteger('review_count')->default(184);
            $table->string('photo_path')->nullable();
        });

        Schema::table('member_profiles', function (Blueprint $table) {
            $table->string('qr_token')->nullable()->unique();
        });

        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pt_session_id')->unique()->constrained('pt_sessions')->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }
};
