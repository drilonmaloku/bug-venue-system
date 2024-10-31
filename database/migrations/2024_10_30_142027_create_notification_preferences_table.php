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
        Schema::create('notification_preferences', function (Blueprint $table) {
              $table->id();
              $table->foreignId('user_id')->constrained()->onDelete('cascade');
              $table->json('preferences')->default(json_encode([
                'coment-added' => true,
                'comment-deleted' => true,
                'discount-added' => true,
                'discount-updated' => true,
                'discount-deleted' => true,
                'invoices-added' => true,
                'invoices-deleted' => true,
                'reservation-added' => true,
                'reservation-deleted' => true,
                'reservation-updated' => true,
                'staff-added' => true,
                'staff-deleted' => true,
                'payments-added' => true,
                'payments-deleted' => true,
                'expenses-added' => true,
                'expenses-updated' => true,
                'expenses-deleted' => true,
                'menu-added' => true,
                'menu-updated' => true,
                'menu-deleted' => true,
                'client-updated' => true,
                'venue-added' => true,
                'venue-updated' => true,
                'venue-deleted' => true,
                ]));
              $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
