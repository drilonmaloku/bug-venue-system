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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->date('date');
            $table->text('description');
            $table->double('amount');
            $table->unsignedBigInteger('location_id');

            $table->timestamps(); 
          
   
            
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
        Schema::dropIfExists('expenses');
    }
};
