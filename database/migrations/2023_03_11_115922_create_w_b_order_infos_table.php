<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWBOrderInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_wb')->create('w_b_order_infos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date', $precision = 0);
            $table->dateTime('lastChangeDate', $precision = 0);
            $table->string('supplierArticle');
            $table->integer('techSize');
            $table->bigInteger('barcode');
            $table->float('totalPrice', 10, 2);
            $table->integer('discountPercent');
            $table->string('warehouseName');
            $table->string('oblast');
            $table->bigInteger('incomeID');
            $table->bigInteger('odid');
            $table->bigInteger('nmId');
            $table->string('subject');
            $table->string('category');
            $table->string('brand');
            $table->boolean('isCancel');
            $table->dateTime('cancel_dt', $precision = 0);
            $table->string('gNumber');
            $table->string('sticker');
            $table->string('srid');
            $table->unique(['odid'], 'unique_w_b_order_infos');
            


/*
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            date DATETIME,
            lastChangeDate DATETIME,
            supplierArticle VARCHAR(255),
            techSize INT,
            barcode BIGINT,
            totalPrice DECIMAL(10,2),
            discountPercent INT,
            warehouseName VARCHAR(255),
            oblast VARCHAR(255),
            incomeID BIGINT,
            odid BIGINT,
            nmId BIGINT,
            subject VARCHAR(255),
            category VARCHAR(255),
            brand VARCHAR(255),
            isCancel BOOLEAN,
            cancel_dt DATETIME,
            /* значение не помещается в bigint */
            /*
            gNumber VARCHAR(255),
            sticker VARCHAR(255),
            srid VARCHAR(255)
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
        Schema::dropIfExists('w_b_order_infos');
    }
}
