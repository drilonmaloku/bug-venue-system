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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('installment_id')->nullable()->after('reservation_id')->constrained('payment_installments')->onDelete('set null');
            $table->string('transaction_reference')->nullable()->after('payment_method');
            $table->string('status')->default('completed')->after('transaction_reference'); // pending, completed, failed, refunded, partial_refund, cancelled
            $table->index('installment_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['installment_id']);
            $table->dropColumn(['installment_id', 'transaction_reference', 'status']);
        });
    }
};
