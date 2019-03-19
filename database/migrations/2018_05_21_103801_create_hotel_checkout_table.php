<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelCheckoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_checkout', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_key');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('tariff_id');
            $table->integer('guests');
            $table->double('subtotal')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_checkout');
    }
}
