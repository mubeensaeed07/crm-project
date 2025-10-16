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
        Schema::create('salary_payment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_month'); // Format: "2025-10" for October 2025
            $table->string('payment_year'); // Format: "2025"
            $table->string('status'); // paid, pending, overdue
            $table->unsignedBigInteger('paid_by'); // ID of admin/supervisor who marked as paid
            $table->string('paid_by_type'); // admin, supervisor, user
            $table->string('paid_by_name'); // Name of person who marked as paid
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_date')->nullable(); // When payment was due
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Note: paid_by can be null, so we'll handle this in the application logic
            
            // Indexes for better performance
            $table->index(['user_id', 'payment_month']);
            $table->index(['payment_year', 'payment_month']);
            $table->index(['status', 'payment_month']);
            $table->index('paid_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payment_history');
    }
};