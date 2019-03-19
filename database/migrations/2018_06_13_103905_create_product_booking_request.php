<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBookingRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('product_booking_request')) {
            Schema::create('product_booking_request', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('cart_id');
                $table->string('booking_id', 20);
                $table->integer('merchant_key');
                $table->integer('product_id');
                $table->integer('quantity');
                $table->double('total');
                $table->text('status');
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
        //
        Schema::drop('product_booking_request');
    }
}
