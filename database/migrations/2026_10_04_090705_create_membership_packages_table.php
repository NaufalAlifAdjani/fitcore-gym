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
        Schema::create('membership_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('basic');
            $table->unsignedInteger('duration_days')->default(30);
            $table->decimal('price', 12, 2);
            $table->text('facilities')->nullable();
            $table->unsignedInteger('pt_session_count')->default(0);
            $table->string('status')->default('active');
            $table->string('badge')->nullable();
            $table->string('tier')->default('Basic');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('promo_price')->nullable();
            $table->unsignedInteger('duration_value')->default(1);
            $table->string('duration_unit')->default('Bulan');
            $table->unsignedInteger('duration_in_days')->nullable();
            $table->unsignedInteger('pt_sessions')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_packages');
    }
};
