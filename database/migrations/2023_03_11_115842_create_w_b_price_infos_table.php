<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWBPriceInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_wb')->create('w_b_price_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('nmId');
            $table->integer('price');
            $table->integer('discount');
            $table->integer('promoCode');
            $table->dateTime('date', $precision = 0);

/*
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nmId INT(11) NOT NULL,
            price INT(11) NOT NULL,
            discount INT(11) NOT NULL,
            promoCode INT(11) NOT NULL
            */


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('w_b_price_infos');
    }
}
