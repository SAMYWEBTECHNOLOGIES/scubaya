<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantDiveCenterInvoicesMappersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('merchant_x_dive_center_invoices')) {
            Schema::create('merchant_x_dive_center_invoices', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key');
                $table->integer('invoice_id');
                $table->integer('course_id');
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
        Schema::dropIfExists('merchant_dive_center_invoices_mappers');
    }
}
