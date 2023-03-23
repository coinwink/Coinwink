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
        Schema::create('cw_alerts_email_per', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('user_id');
            $table->string('coin', 300);
            $table->string('coin_id', 32);
            $table->string('symbol', 64);
            $table->string('price_set_btc', 64);
            $table->string('price_set_usd', 64);
            $table->string('price_set_eth', 64);
            $table->string('plus_percent', 64);
            $table->string('plus_change', 64);
            $table->string('plus_compared', 64);
            $table->string('minus_percent', 64);
            $table->string('minus_change', 64);
            $table->string('minus_compared', 64);
            $table->string('plus_sent', 1);
            $table->string('minus_sent', 1);
            $table->string('email', 300);
            $table->string('unique_id', 64);
            $table->string('timestamp', 64);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cw_alerts_email_per');
    }
};
