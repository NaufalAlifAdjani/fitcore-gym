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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->unique();
            $table->foreignId('member_id')->constrained('users')->restrictOnDelete();
            $table->unsignedBigInteger('package_id');
            $table->enum('package_type', ['membership', 'pt_session']);
            $table->unsignedBigInteger('amount');
            $table->string('bank_sender');
            $table->string('bank_destination');
            $table->string('proof_image_url');
            $table->dateTime('transfer_date');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'transfer_date']);
            $table->index(['package_type', 'package_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
