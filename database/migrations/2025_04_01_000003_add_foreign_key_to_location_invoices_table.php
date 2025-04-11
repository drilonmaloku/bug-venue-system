<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('location_invoices', function (Blueprint $table) {
            $table->foreign('location_credit_deposit_id')
                  ->references('id')
                  ->on('location_credit_deposits')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('location_invoices', function (Blueprint $table) {
            $table->dropForeign(['location_credit_deposit_id']);
        });
    }
};
