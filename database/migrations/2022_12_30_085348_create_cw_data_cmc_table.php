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
        Schema::create('cw_data_cmc', function (Blueprint $table) {
            $table->integer('ID', true)->unique('ID');
            $table->mediumText('json');
        });

        DB::table('cw_data_cmc')->insert(
            array(
                'ID' => 1,
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
        Schema::dropIfExists('cw_data_cmc');
    }
};