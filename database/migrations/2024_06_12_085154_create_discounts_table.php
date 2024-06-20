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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->integer('amount')->nullable();
            $table->unsignedBigInteger("reservation_id");
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();


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
        Schema::dropIfExists('discounts');
    }
};
