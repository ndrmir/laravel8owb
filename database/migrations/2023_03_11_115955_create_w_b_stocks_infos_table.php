<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWBStocksInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_wb')->create('w_b_stocks_infos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('lastChangeDate', $precision = 0);
            $table->string('supplierArticle');
            $table->integer('techSize')->nullable();
            $table->string('barcode');
            $table->integer('quantity');
            $table->boolean('isSupply');
            $table->boolean('isRealization');
            $table->integer('quantityFull');
            $table->string('warehouseName');
            $table->integer('nmId');
            $table->string('subject');
            $table->string('category');
            $table->integer('daysOnSite');
            $table->string('brand');
            $table->string('SCCode');
            $table->float('Price', 10, 2);
            $table->float('Discount', 10, 2);
            $table->dateTime('date', $precision = 0);

/*
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            lastChangeDate DATETIME,
            supplierArticle VARCHAR(255),
            techSize INT DEFAULT NULL,
            barcode VARCHAR(255),
            quantity INT,
            isSupply BOOLEAN,
            isRealization BOOLEAN,
            quantityFull INT,
            warehouseName VARCHAR(255),
            nmId INT,
            subject VARCHAR(255),
            category VARCHAR(255),
            daysOnSite INT,
            brand VARCHAR(255),
            SCCode VARCHAR(255),
            Price DECIMAL(10,2),
            Discount DECIMAL(10,2),
            date DATETIME
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
        Schema::dropIfExists('w_b_stocks_infos');
    }
}
