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
        Schema::create('reservation_guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('guest_count')->default(1);
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->integer('status')->nullable();
            $table->boolean('is_checked_in')->default(false);
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('location_id');
            $table->timestamps();


            $table->foreign('reservation_id')
                ->references('id')
                ->on('reservations')
                ->onDelete("cascade");
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
        Schema::dropIfExists('reservation_guests');
    }
};
