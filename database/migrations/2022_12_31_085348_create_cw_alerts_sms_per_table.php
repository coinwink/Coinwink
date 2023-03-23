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
        Schema::create('cw_alerts_sms_per', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('user_ID');
            $table->string('coin', 64);
            $table->integer('coin_id');
            $table->string('symbol', 32);
            $table->string('price_set_btc', 32);
            $table->string('price_set_usd', 32);
            $table->string('price_set_eth', 32);
            $table->string('plus_percent', 32);
            $table->string('plus_change', 32);
            $table->string('plus_compared', 32);
            $table->string('minus_percent', 32);
            $table->string('minus_change', 32);
            $table->string('minus_compared', 32);
            $table->string('plus_sent', 1);
            $table->string('minus_sent', 1);
            $table->string('phone', 32);
            $table->dateTime('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cw_alerts_sms_per');
    }
};
