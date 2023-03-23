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
        Schema::create('cw_alerts_portfolio', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('user_ID');
            $table->integer('change_1h_plus');
            $table->integer('change_1h_minus');
            $table->integer('change_24h_plus');
            $table->integer('change_24h_minus');
            $table->string('on_1h_plus', 10);
            $table->string('on_1h_minus', 10);
            $table->string('on_24h_plus', 10);
            $table->string('on_24h_minus', 10);
            $table->string('type', 32)->default('email');
            $table->string('destination', 132);
            $table->dateTime('timestamp');
            $table->integer('expanded');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cw_alerts_portfolio');
    }
};
