<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cw_subs', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('user_ID');
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->date('date_renewed');
            $table->string('status', 64);
            $table->string('plan', 32);
            $table->integer('months')->default(1);
            $table->string('payment_ID', 64);
            $table->string('subscription', 64);
            $table->string('customer', 64);
            $table->dateTime('date_cancelled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cw_subs');
    }
};
