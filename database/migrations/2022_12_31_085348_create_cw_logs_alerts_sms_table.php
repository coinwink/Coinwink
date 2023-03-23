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
        Schema::create('cw_logs_alerts_sms', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('user_ID')->nullable();
            $table->string('alert_ID', 64);
            $table->integer('coin_ID');
            $table->string('name', 64);
            $table->text('symbol');
            $table->text('type')->nullable();
            $table->text('destination')->nullable();
            $table->text('status')->nullable();
            $table->string('error', 1280)->nullable();
            $table->timestamp('timestamp')->useCurrentOnUpdate()->useCurrent();
            $table->integer('time');
            $table->string('content', 9999);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cw_logs_alerts_sms');
    }
};
