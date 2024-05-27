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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('client_id');
            $table->string('date');
            $table->double('value');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('reservation_id')
                ->references('id')
                ->on('reservations')
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
        Schema::dropIfExists('payments');
    }
};
