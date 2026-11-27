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
        Schema::table('member_pt_quotas', function (Blueprint $table) {
            $table->foreignId('trainer_id')->nullable()->after('pt_package_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_pt_quotas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('trainer_id');
        });
    }
};
