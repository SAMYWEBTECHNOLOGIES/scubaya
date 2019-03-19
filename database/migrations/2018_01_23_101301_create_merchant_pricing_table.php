<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantPricingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('merchant_pricing_settings')) {
            Schema::create('merchant_pricing_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key');
                $table->boolean('active_commission');
                $table->boolean('charge_commission_merchant');
                $table->boolean('charge_commission');
                $table->boolean('auto_block');
                $table->boolean('website_level');
                $table->char('unpaid_invoices',100);
                $table->boolean('charge_commission_shop');
                $table->integer('commission_dive_center')->nullable();
                $table->integer('commission_dive_hotel')->nullable();
                $table->integer('commission_dive_shop')->nullable();
                $table->integer('commission_percentage')->nullable();
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
        Schema::dropIfExists('merchant_pricing_settings');
    }
}
