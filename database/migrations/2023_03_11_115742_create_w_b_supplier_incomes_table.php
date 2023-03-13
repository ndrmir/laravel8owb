<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWBSupplierIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_wb')->create('w_b_supplier_incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('incomeId');
            $table->string('number');
            $table->dateTime('date', $precision = 0);
            $table->dateTime('lastChangeDate', $precision = 0);
            $table->string('supplierArticle');
            $table->integer('techSize');
            $table->string('barcode');
            $table->integer('quantity');
            $table->float('totalPrice', 10, 2);
            $table->dateTime('dateClose', $precision = 0);
            $table->string('warehouseName');
            $table->integer('nmId');
            $table->string('status');
            $table->unique(['incomeId', 'barcode'], 'unique_w_b_supplier_incomes');

            /*
            id INT(11) NOT NULL AUTO_INCREMENT,
            incomeId INT(11) NOT NULL,
            number VARCHAR(255),
            date DATETIME,
            lastChangeDate DATETIME,
            supplierArticle VARCHAR(255),
            techSize INT(11),
            barcode VARCHAR(255),
            quantity INT(11),
            totalPrice DECIMAL(10,2),
            dateClose DATETIME,
            warehouseName VARCHAR(255),
            nmId INT(11),
            status VARCHAR(255),
            PRIMARY KEY (id)
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
        Schema::dropIfExists('w_b_supplier_incomes');
    }
}
