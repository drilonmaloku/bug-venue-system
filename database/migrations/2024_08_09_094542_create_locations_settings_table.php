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
        Schema::create('locations_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->json('settings')->nullable();
            $table->timestamps();


            
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
        Schema::dropIfExists('locations_settings');
    }
};
