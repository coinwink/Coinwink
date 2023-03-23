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
        Schema::create('cw_logs_subs_susp', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('user_ID');
            $table->dateTime('timestamp');
            $table->string('status', 64);
            $table->string('payment_ID', 64);
            $table->mediumText('pending');
            $table->mediumText('errors');
            $table->text('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cw_logs_subs_susp');
    }
};
