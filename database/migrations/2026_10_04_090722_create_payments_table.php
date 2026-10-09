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
            $table->string('invoice_id')->nullable()->unique();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('membership_id')->nullable()->constrained('memberships')->nullOnDelete();
            $table->foreignId('pt_package_id')->nullable()->constrained('pt_packages')->nullOnDelete();
            $table->foreignId('bank_id')->constrained('banks');
            $table->string('payment_type'); // membership / pt_package
            $table->unsignedBigInteger('package_id')->nullable();
            $table->enum('package_type', ['membership', 'pt_session'])->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('bank_sender')->nullable();
            $table->string('bank_destination')->nullable();
            $table->string('proof_image_path')->nullable();
            $table->string('proof_image_url')->nullable();
            $table->dateTime('transfer_date')->nullable();
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
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
