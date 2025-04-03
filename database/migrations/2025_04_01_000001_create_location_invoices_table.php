<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('location_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->integer('credits');
            $table->string('invoice_number')->unique();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->unsignedBigInteger('location_credit_deposit_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('location_invoices');
    }
};