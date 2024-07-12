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
        Schema::create('reservations_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("reservation_id");
            $table->unsignedBigInteger("user_id");
            $table->text('comment');
            $table->unsignedBigInteger('location_id');
            $table->timestamps();


            $table->foreign("reservation_id")
            ->references("id")
            ->on("reservations")
            ->onDelete("cascade")
            ->required();

            $table->foreign("user_id")
            ->references("id")
            ->on("users")
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
        Schema::dropIfExists('reservations_comments');
    }
};
