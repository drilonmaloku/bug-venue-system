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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('amount')->nullable();
            $table->unsignedBigInteger("reservation_id");
            $table->date('date');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('location_id');

            $table->timestamps();


            $table->foreign("reservation_id")
            ->references("id")
            ->on("reservations")
            ->onDelete("cascade")
            ->required();

            $table->foreign('location_id')
            ->references('id')
            ->on('locations')
            ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
