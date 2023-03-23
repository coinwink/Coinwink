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
        Schema::create('cw_settings', function (Blueprint $table) {
            $table->integer('user_ID')->primary();
            $table->tinyInteger('subs')->default(0);
            $table->smallInteger('sms')->default(0);
            $table->tinyInteger('legac')->default(0);
            $table->string('theme', 32);
            $table->boolean('t_s');
            $table->string('t_i', 4);
            $table->dateTime('last_login')->nullable();
            $table->string('unique_id', 100);
            $table->string('email', 100);
            $table->string('phone_nr', 100);
            $table->string('tg_user', 128);
            $table->string('tg_id', 64);
            $table->string('cw_tab', 32);
            $table->integer('conv_exp');
            $table->string('cur_main', 10);
            $table->string('cur_p', 10);
            $table->string('cur_w', 10);
            $table->string('conf_w', 10);
            $table->text('portfolio');
            $table->text('watchlist');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cw_settings');
    }
};
