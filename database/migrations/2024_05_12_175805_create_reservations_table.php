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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('client_id');
            $table->double('number_of_guests')->nullable();
            $table->string('date')->nullable();
            $table->boolean('full_day')->nullable();
            $table->string('description')->nullable();
            $table->double('current_payment')->nullable();
            $table->double('total_payment')->nullable();
            $table->timestamps();

            $table->foreign('venue_id')
                ->references('id')
                ->on('venues')
                ->onDelete("cascade");
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
