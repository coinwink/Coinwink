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
        Schema::create('cw_data_cur_rates', function (Blueprint $table) {
            $table->integer('ID')->unique('ID');
            $table->string('EUR', 32);
            $table->string('GBP', 32);
            $table->string('CAD', 32);
            $table->string('AUD', 32);
            $table->string('BRL', 32);
            $table->string('MXN', 32);
            $table->string('JPY', 32);
            $table->string('SGD', 32);
        });

        DB::table('cw_data_cur_rates')->insert(
            array(
                'ID' => 1,
                'EUR' => '0.932804',
                'GBP' => '0.830289',
                'CAD' => '1.34745',
                'AUD' => '1.453704',
                'BRL' => '5.168041',
                'MXN' => '18.361204',
                'JPY' => '134.10504',
                'SGD' => '1.337104'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cw_data_cur_rates');
    }
};
