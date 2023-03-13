<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWBSalesInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_wb')->create('w_b_sales_infos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date', $precision = 0);
            $table->dateTime('lastChangeDate', $precision = 0);
            $table->string('supplierArticle');
            $table->integer('techSize')->nullable();
            $table->bigInteger('barcode');
            $table->float('totalPrice', 10, 2);
            $table->integer('discountPercent');
            $table->boolean('isSupply');
            $table->boolean('isRealization');
            $table->float('promoCodeDiscount', 10, 2);
            $table->string('warehouseName');
            $table->string('countryName');
            $table->string('oblastOkrugName');
            $table->string('regionName');
            $table->bigInteger('incomeID');
            $table->string('saleID');
            $table->bigInteger('odid');
            $table->integer('spp');
            $table->float('forPay', 10, 2);
            $table->float('finishedPrice', 10, 2);
            $table->float('priceWithDisc', 10, 2);
            $table->bigInteger('nmId');
            $table->string('subject');
            $table->string('category');
            $table->string('brand');
            $table->boolean('IsStorno');
            $table->string('gNumber');
            $table->string('sticker');
            $table->string('srid');
            $table->unique(['saleID'], 'unique_w_b_sales_infos');

            /*
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            date DATETIME,
            lastChangeDate DATETIME,
            supplierArticle VARCHAR(255),
            techSize INT,
            barcode BIGINT(20),
            totalPrice DECIMAL(10,2),
            discountPercent INT,
            isSupply BOOLEAN,
            isRealization BOOLEAN,
            promoCodeDiscount DECIMAL(10,2),
            warehouseName VARCHAR(255),
            countryName VARCHAR(255),
            oblastOkrugName VARCHAR(255),
            regionName VARCHAR(255),
            incomeID BIGINT(20),
            saleID VARCHAR(255),
            odid BIGINT(20),
            spp INT,
            forPay DECIMAL(10,2),
            finishedPrice DECIMAL(10,2),
            priceWithDisc DECIMAL(10,2),
            nmId BIGINT(20),
            subject VARCHAR(255),
            category VARCHAR(255),
            brand VARCHAR(255),
            IsStorno BOOLEAN,
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
        Schema::dropIfExists('w_b_sales_infos');
    }
}
