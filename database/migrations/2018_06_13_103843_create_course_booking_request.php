<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseBookingRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('course_booking_request')){
            Schema::create('course_booking_request', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('cart_id');
                $table->string('booking_id', 20);
                $table->integer('merchant_key');
                $table->integer('course_id');
                $table->integer('no_of_people');
                $table->text('divers');
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
        //
        Schema::drop('course_booking_request');
    }
}
