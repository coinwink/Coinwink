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
        Schema::create('cw_alerts_email_cur', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('user_id');
            $table->string('coin', 300);
            $table->string('coin_id', 64);
            $table->string('symbol', 64);
            $table->string('below', 64);
            $table->string('below_currency', 64);
            $table->string('above', 64);
            $table->string('above_currency', 64);
            $table->string('below_sent', 32);
            $table->string('above_sent', 32);
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
        Schema::dropIfExists('cw_alerts_email_cur');
    }
};
