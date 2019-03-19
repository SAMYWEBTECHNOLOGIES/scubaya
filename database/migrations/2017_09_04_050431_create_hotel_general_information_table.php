<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelGeneralInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('hotels_general_information')) {
            Schema::create('hotels_general_information', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_primary_id');
                //$table->string('hotel_id')->unique();
                $table->string('name');
                $table->string('image');
                $table->text('gallery')->nullable();
                $table->string('address');
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->integer('zipcode')->nullable();
                $table->double('latitude')->nullable();
                $table->double('longitude')->nullable();
                $table->text('hotel_desc')->nullable();
                $table->text('hotel_policies')->nullable();
                $table->tinyInteger('is_hotel_popular')->default('0');
                $table->tinyInteger('status')->default('1');
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
        Schema::dropIfExists('hotel_general_information');
    }
}
