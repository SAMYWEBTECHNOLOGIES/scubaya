<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelBookingRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('hotel_booking_request')) {
            Schema::create('hotel_booking_request', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('cart_id');
                $table->string('booking_id', 20);
                $table->integer('merchant_key');
                $table->integer('tariff_id');
                $table->date('check_in');
                $table->date('check_out');
                $table->integer('no_of_persons');
                $table->double('total');
                $table->string('status', 20);
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
        Schema::dropIfExists('hotel_booking_request');
    }
}
