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
        Schema::create('pt_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('pt_session_count');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('min_membership_days')->default(0);
            $table->unsignedInteger('validity_days');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt_packages');
    }
};
