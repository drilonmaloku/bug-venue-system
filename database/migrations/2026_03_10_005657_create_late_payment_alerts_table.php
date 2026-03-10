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
        Schema::create('late_payment_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('installment_id')->constrained('payment_installments')->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('severity'); // low, medium, high, critical
            $table->string('status')->default('active'); // active, acknowledged, resolved, escalated, ignored
            $table->integer('days_overdue');
            $table->decimal('amount_overdue', 15, 2);
            $table->date('alert_date');
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('escalated_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('escalated_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('payment_schedule_id');
            $table->index('installment_id');
            $table->index('client_id');
            $table->index('severity');
            $table->index('status');
            $table->index('alert_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('late_payment_alerts');
    }
};
