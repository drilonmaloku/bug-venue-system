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
        Schema::create('decors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('files')->onDelete('set null');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decors');
    }
};
