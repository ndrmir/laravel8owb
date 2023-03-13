<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOzonFboPostingListFlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('ozon_fbo_posting_list_flats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->string('order_number');
            $table->string('posting_number');
            $table->string('status');
            $table->integer('cancel_reason_id');
            $table->dateTime('created_at', $precision = 0);
            $table->dateTime('in_process_at', $precision = 0);
            $table->json('additional_data')->nullable();
            $table->integer('products_sku');
            $table->string('products_name');
            $table->integer('products_quantity');
            $table->string('products_offer_id');
            $table->float('products_price', 7, 2)->nullable();
            $table->json('products_digital_codes')->nullable();
            $table->string('products_currency_code');
            $table->string('AD_region');
            $table->string('AD_city');
            $table->string('AD_delivery_type');
            $table->boolean('AD_is_premium')->default('0');
            $table->string('AD_payment_type_group_name');
            $table->bigInteger('AD_warehouse_id');
            $table->string('AD_warehouse_name');
            $table->boolean('AD_is_legal')->default('0');
            $table->string('FD_cluster_from');
            $table->string('FD_cluster_to');
            $table->integer('FD_products_commission_amount');
            $table->integer('FD_products_commission_percent');
            $table->integer('FD_products_payout');
            $table->bigInteger('FD_products_product_id');
            $table->integer('FD_products_old_price')->nullable();
            $table->integer('FD_products_price')->nullable();
            $table->integer('FD_products_total_discount_value');
            $table->integer('FD_products_total_discount_percent');
            $table->longText('FD_products_actions');
            $table->string('FD_products_picking')->nullable();
            $table->integer('FD_products_quantity');
            $table->integer('FD_products_client_price')->nullable();
            $table->string('FD_products_currency_code');
            $table->integer('FD_products_IS_MSI_fulfillment');
            $table->integer('FD_products_IS_MSI_pickup');
            $table->integer('FD_products_IS_MSI_dropoff_pvz');
            $table->integer('FD_products_IS_MSI_dropoff_sc');
            $table->integer('FD_products_IS_MSI_dropoff_ff');
            $table->integer('FD_products_IS_MSI_direct_flow_trans');
            $table->integer('FD_products_IS_MSI_return_flow_trans');
            $table->integer('FD_products_IS_MSI_deliv_to_customer');
            $table->integer('FD_products_IS_MSI_return_not_deliv_to_customer');
            $table->integer('FD_products_IS_MSI_return_part_goods_customer');
            $table->integer('FD_products_IS_MSI_return_after_deliv_to_customer');
            $table->integer('FD_PS_MSI_fulfillment')->nullable();
            $table->integer('FD_PS_MSI_pickup')->nullable();
            $table->integer('FD_PS_MSI_dropoff_pvz')->nullable();
            $table->integer('FD_PS_MSI_dropoff_sc')->nullable();
            $table->integer('FD_PS_MSI_dropoff_ff')->nullable();
            $table->integer('FD_PS_MSI_direct_flow_trans')->nullable();
            $table->integer('FD_PS_MSI_return_flow_trans')->nullable();
            $table->integer('FD_PS_MSI_deliv_to_customer')->nullable();
            $table->integer('FD_PS_MSI_return_not_deliv_to_customer')->nullable();
            $table->integer('FD_PS_MSI_return_part_goods_customer')->nullable();
            $table->integer('FD_PS_MSI_return_after_deliv_to_customer')->nullable();
            $table->unique(['order_id', 'posting_number', 'products_sku'], 'unique_ozon_fbo_posting_list_flats');


            /*
            $table_def = "id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
            $table_def .= "order_id BIGINT NOT NULL,";
            $table_def .= "order_number VARCHAR(30) BINARY NOT NULL,";
            $table_def .= "posting_number VARCHAR(30) BINARY NOT NULL,";
            $table_def .= "status VARCHAR(30) BINARY NOT NULL,";
            $table_def .= "cancel_reason_id INT NOT NULL,";
            $table_def .= "created_at TIMESTAMP,";
            $table_def .= "in_process_at TIMESTAMP,";
            $table_def .= "additional_data JSON DEFAULT NULL,";
            $table_def .= "products_sku INT NOT NULL,";
            $table_def .= "products_name VARCHAR(2000) BINARY NOT NULL,";
            $table_def .= "products_quantity INT NOT NULL,";
            $table_def .= "products_offer_id VARCHAR(200) BINARY NOT NULL,";
            $table_def .= "products_price FLOAT(7,2) UNSIGNED DEFAULT NULL,";
            $table_def .= "products_digital_codes JSON DEFAULT NULL,";
            $table_def .= "products_currency_code VARCHAR(20) BINARY NOT NULL,";
            $table_def .= "AD_region VARCHAR(50) BINARY NOT NULL,";
            $table_def .= "AD_city VARCHAR(50) BINARY NOT NULL,";
            $table_def .= "AD_delivery_type VARCHAR(20) BINARY NOT NULL,";
            $table_def .= "AD_is_premium BOOLEAN NOT NULL DEFAULT 0,";
            $table_def .= "AD_payment_type_group_name VARCHAR(50) BINARY NOT NULL,";
            $table_def .= "AD_warehouse_id BIGINT NOT NULL,";
            $table_def .= "AD_warehouse_name VARCHAR(50) BINARY NOT NULL,";
            $table_def .= "AD_is_legal BOOLEAN NOT NULL DEFAULT 0,";
            $table_def .= "FD_cluster_from VARCHAR(50) BINARY NOT NULL,";
            $table_def .= "FD_cluster_to VARCHAR(50) BINARY NOT NULL,";
            $table_def .= "FD_products_commission_amount INT NOT NULL,";
            $table_def .= "FD_products_commission_percent INT NOT NULL,";
            $table_def .= "FD_products_payout INT NOT NULL,";
            $table_def .= "FD_products_product_id BIGINT NOT NULL,";
            $table_def .= "FD_products_old_price INT DEFAULT NULL,";
            $table_def .= "FD_products_price INT DEFAULT NULL,";
            $table_def .= "FD_products_total_discount_value INT NOT NULL,";
            $table_def .= "FD_products_total_discount_percent INT NOT NULL,";
            $table_def .= "FD_products_actions TEXT(10000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,";
            $table_def .= "FD_products_picking VARCHAR(50) BINARY,";
            $table_def .= "FD_products_quantity INT NOT NULL,";
            $table_def .= "FD_products_client_price INT DEFAULT NULL,";
            $table_def .= "FD_products_currency_code VARCHAR(20) BINARY NOT NULL,";
            $table_def .= "FD_products_IS_MSI_fulfillment INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_pickup INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_dropoff_pvz INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_dropoff_sc INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_dropoff_ff INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_direct_flow_trans INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_return_flow_trans INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_deliv_to_customer INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_return_not_deliv_to_customer INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_return_part_goods_customer INT NOT NULL,";
            $table_def .= "FD_products_IS_MSI_return_after_deliv_to_customer INT NOT NULL,";
            $table_def .= "FD_PS_MSI_fulfillment INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_pickup INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_dropoff_pvz INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_dropoff_sc INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_dropoff_ff INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_direct_flow_trans INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_return_flow_trans INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_deliv_to_customer INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_return_not_deliv_to_customer INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_return_part_goods_customer INT DEFAULT NULL,";
            $table_def .= "FD_PS_MSI_return_after_deliv_to_customer INT DEFAULT NULL";
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
        Schema::dropIfExists('ozon_fbo_posting_list_flats');
    }
}
