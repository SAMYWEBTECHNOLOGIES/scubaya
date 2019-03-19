<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantProductInvoicesMappersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('merchant_x_product_invoices')) {
            Schema::create('merchant_x_product_invoices', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key');
                $table->integer('invoice_id');
                $table->integer('product_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_x_dive_center_invoices');
    }
}
