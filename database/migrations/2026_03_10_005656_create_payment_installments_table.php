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
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_schedule_id')->constrained()->onDelete('cascade');
            $table->string('type'); // deposit, interim, final, milestone, custom
            $table->integer('sequence');
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('percentage_of_total', 5, 2);
            $table->date('due_date');
            $table->string('status')->default('pending'); // pending, partial, paid, overdue, waived, cancelled
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('amount_outstanding', 15, 2);
            $table->date('paid_date')->nullable();
            $table->date('overdue_since')->nullable();
            $table->decimal('late_fees_accrued', 15, 2)->default(0);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('overdue_notice_sent_at')->nullable();
            $table->timestamp('final_notice_sent_at')->nullable();
            $table->integer('reminder_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('payment_schedule_id');
            $table->index('due_date');
            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_installments');
    }
};
