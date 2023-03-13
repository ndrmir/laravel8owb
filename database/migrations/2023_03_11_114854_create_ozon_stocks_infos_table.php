<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOzonStocksInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('ozon_stocks_infos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->string('offer_id');
            $table->integer('fbs_present')->nullable();
            $table->integer('fbs_reserved')->nullable();
            $table->integer('fbo_present')->nullable();
            $table->integer('fbo_reserved')->nullable();
            $table->dateTime('date', $precision = 0);

            /*
            $table_def = "id BIGINT NOT NULL AUTO_INCREMENT,";
            $table_def .= "product_id BIGINT NOT NULL,";
            $table_def .= "offer_id VARCHAR(30) BINARY NOT NULL,";
            $table_def .= "fbs_present INT NOT NULL,";
            $table_def .= "fbs_reserved INT NOT NULL,";
            $table_def .= "fbo_present INT NOT NULL,";
            $table_def .= "fbo_reserved INT NOT NULL,";
            $table_def .= "date TIMESTAMP,";
            $table_def .= "PRIMARY KEY (id)";
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
        Schema::dropIfExists('ozon_stocks_infos');
    }
}
