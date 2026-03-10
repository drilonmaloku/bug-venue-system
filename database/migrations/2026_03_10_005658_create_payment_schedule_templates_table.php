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
        Schema::create('payment_schedule_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type')->unique(); // standard_3_tier, equal_split, full_upfront, corporate_60_day, monthly_6
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('installment_config');
            $table->integer('grace_period_days')->default(3);
            $table->boolean('late_fees_enabled')->default(false);
            $table->decimal('late_fee_percentage', 5, 4)->default(0.02);
            $table->integer('reminder_days_before')->default(7);
            $table->timestamps();

            // Indexes
            $table->index('type');
            $table->index('is_active');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_schedule_templates');
    }
};
