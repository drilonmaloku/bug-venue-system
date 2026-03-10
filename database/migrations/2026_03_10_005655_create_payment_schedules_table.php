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
        Schema::create('payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('template_type')->default('standard_3_tier');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('total_outstanding', 15, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('status')->default('draft'); // draft, active, completed, cancelled, suspended
            $table->date('event_date');
            $table->integer('grace_period_days')->default(3);
            $table->boolean('late_fees_enabled')->default(false);
            $table->decimal('late_fee_percentage', 5, 4)->default(0.02);
            $table->decimal('late_fee_cap', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('reservation_id');
            $table->index('client_id');
            $table->index('status');
            $table->index('template_type');
            $table->index('event_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_schedules');
    }
};
