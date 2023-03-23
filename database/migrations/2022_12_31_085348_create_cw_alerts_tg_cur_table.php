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
        Schema::create('cw_alerts_tg_cur', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->text('user_ID');
            $table->text('coin');
            $table->text('coin_id');
            $table->text('symbol');
            $table->text('below');
            $table->text('below_currency');
            $table->text('above');
            $table->text('above_currency');
            $table->text('below_sent');
            $table->text('above_sent');
            $table->string('tg_id', 64);
            $table->string('tg_user', 128);
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
        Schema::dropIfExists('cw_alerts_tg_cur');
    }
};
