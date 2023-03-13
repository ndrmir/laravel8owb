<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWBSalerReportByRealisationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_wb')->create('w_b_saler_report_by_realisations', function (Blueprint $table) {
            $table->id();
            $table->integer('realizationreport_id');
            $table->dateTime('date_from', $precision = 0);
            $table->dateTime('date_to', $precision = 0);
            $table->dateTime('create_dt', $precision = 0)->nullable();
            $table->string('suppliercontract_code')->nullable();
            $table->bigInteger('rrd_id');
            $table->integer('gi_id');
            $table->string('subject_name')->nullable();
            $table->integer('nm_id')->nullable();
            $table->string('brand_name');
            $table->string('sa_name');
            $table->string('ts_name');
            $table->string('barcode');
            $table->string('doc_type_name');
            $table->integer('quantity');
            $table->float('retail_price');
            $table->float('retail_amount');
            $table->float('sale_percent');
            $table->float('commission_percent');
            $table->string('office_name')->nullable();
            $table->string('supplier_oper_name');
            $table->dateTime('order_dt', $precision = 0);
            $table->dateTime('sale_dt', $precision = 0);
            $table->dateTime('rr_dt', $precision = 0);
            $table->bigInteger('shk_id');
            $table->float('retail_price_withdisc_rub');
            $table->integer('delivery_amount');
            $table->bigInteger('return_amount');
            $table->float('delivery_rub');
            $table->string('gi_box_type_name');
            $table->float('product_discount_for_report');
            $table->float('supplier_promo');
            $table->bigInteger('rid');
            $table->float('ppvz_spp_prc');
            $table->float('ppvz_kvw_prc_base');
            $table->float('ppvz_kvw_prc');
            $table->float('ppvz_sales_commission');
            $table->float('ppvz_for_pay');
            $table->float('ppvz_reward');
            $table->float('acquiring_fee');
            $table->string('acquiring_bank')->nullable();
            $table->float('ppvz_vw');
            $table->float('ppvz_vw_nds');
            $table->integer('ppvz_office_id');
            $table->string('ppvz_office_name')->nullable();
            $table->integer('ppvz_supplier_id');
            $table->string('ppvz_supplier_name')->nullable();
            $table->string('ppvz_inn')->nullable();
            $table->string('declaration_number')->nullable();
            $table->string('bonus_type_name')->nullable();
            $table->string('sticker_id')->nullable();
            $table->string('site_country');
            $table->float('penalty');
            $table->float('additional_payment');
            $table->string('srid');
            $table->unique(['rrd_id'], 'unique_w_b_saler_report_by_realisations');

/*
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            realizationreport_id INT(11) NOT NULL,
            date_from DATETIME NOT NULL,
            date_to DATETIME NOT NULL,
            create_dt DATETIME DEFAULT NULL,
            suppliercontract_code VARCHAR(255),
            rrd_id BIGINT(20) NOT NULL,
            gi_id INT(11) NOT NULL,
            subject_name VARCHAR(255) DEFAULT NULL,
            nm_id INT(11) DEFAULT NULL,
            brand_name VARCHAR(255) NOT NULL,
            sa_name VARCHAR(255) NOT NULL,
            ts_name VARCHAR(255) NOT NULL,
            barcode VARCHAR(255) NOT NULL,
            doc_type_name VARCHAR(255) NOT NULL,
            quantity INT(11) NOT NULL,
            retail_price FLOAT NOT NULL,
            retail_amount FLOAT NOT NULL,
            sale_percent FLOAT NOT NULL,
            commission_percent FLOAT NOT NULL,
            office_name VARCHAR(255) DEFAULT NULL,
            supplier_oper_name VARCHAR(255) NOT NULL,
            order_dt DATETIME NOT NULL,
            sale_dt DATETIME NOT NULL,
            rr_dt DATETIME NOT NULL,
            shk_id BIGINT(20) NOT NULL,
            retail_price_withdisc_rub FLOAT NOT NULL,
            delivery_amount INT(11) NOT NULL,
            return_amount INT(11) NOT NULL,
            delivery_rub FLOAT NOT NULL,
            gi_box_type_name VARCHAR(255) NOT NULL,
            product_discount_for_report FLOAT NOT NULL,
            supplier_promo FLOAT NOT NULL,
            rid BIGINT(20) NOT NULL,
            ppvz_spp_prc FLOAT NOT NULL,
            ppvz_kvw_prc_base FLOAT NOT NULL,
            ppvz_kvw_prc FLOAT NOT NULL,
            ppvz_sales_commission FLOAT NOT NULL,
            ppvz_for_pay FLOAT NOT NULL,
            ppvz_reward FLOAT NOT NULL,
            acquiring_fee FLOAT NOT NULL,
            acquiring_bank VARCHAR(255),
            ppvz_vw FLOAT NOT NULL,
            ppvz_vw_nds FLOAT NOT NULL,
            ppvz_office_id INT(11) NOT NULL,
            ppvz_office_name VARCHAR(255),
            ppvz_supplier_id INT(11) NOT NULL,
            ppvz_supplier_name VARCHAR(255),
            ppvz_inn VARCHAR(255),
            declaration_number VARCHAR(255),
            bonus_type_name VARCHAR(255),
            sticker_id VARCHAR(255),
            site_country VARCHAR(255) NOT NULL,
            penalty FLOAT NOT NULL,
            additional_payment FLOAT NOT NULL,
            srid VARCHAR(255) NOT NULL
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
        Schema::dropIfExists('w_b_saler_report_by_realisations');
    }
}
