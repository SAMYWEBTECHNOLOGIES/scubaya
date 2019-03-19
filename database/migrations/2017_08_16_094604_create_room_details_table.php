<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('room_details')) {
            Schema::create('room_details', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_primary_id');
                $table->integer('hotel_id');
                $table->string('type');
                $table->string('name')->nullable();
                $table->integer('number')->nullable();
                $table->integer('floor')->nullable();
                $table->integer('max_people');
                $table->text('features')->nullable();
                $table->string('room_image')->nullable();
                $table->string('gallery')->nullable();
                $table->text('description')->nullable();
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
        Schema::dropIfExists('room_details');
    }
}
