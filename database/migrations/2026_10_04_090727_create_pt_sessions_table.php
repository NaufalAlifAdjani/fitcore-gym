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
        Schema::create('pt_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_pt_quota_id')->constrained('member_pt_quotas')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('users')->cascadeOnDelete();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status')->default('scheduled'); // scheduled, done, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt_sessions');
    }
};
