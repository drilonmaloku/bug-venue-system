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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('resolver_id')->nullable();
            $table->text('title');
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();



            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete("cascade");


            $table->foreign('resolver_id')
            ->references('id')
            ->on('users')
            ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
