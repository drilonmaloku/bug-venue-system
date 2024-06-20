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
        Schema::create('pricing_status_tracking', function (Blueprint $table) {
            $table->id();
            $table->integer('price')->nullable();
            $table->integer('number_of_guests')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('menu_price')->nullable();
            $table->integer('total_discount_price')->nullable()->default(0);
            $table->integer('total_invoice_price')->nullable()->default(0);
            $table->unsignedBigInteger("reservation_id");
            $table->unsignedBigInteger("user_id");
            $table->timestamps();

            $table->foreign("user_id")
            ->references("id")
            ->on("users")
            ->onDelete("cascade")
            ->required();

            $table->foreign("reservation_id")
            ->references("id")
            ->on("reservations")
            ->onDelete("cascade")
            ->required();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_status_tracking');
    }
};
