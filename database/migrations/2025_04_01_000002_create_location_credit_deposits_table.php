<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('location_credit_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('deposit_number')->unique();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->integer('credits');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('location_credit_deposits');
    }
}; 