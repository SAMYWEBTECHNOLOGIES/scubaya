<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('boats')) {
            Schema::create('boats', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key');
                $table->integer('dive_center_id');
                $table->boolean('is_boat_active');
                $table->string('name');
                $table->integer('max_passengers');
                $table->double('engine_power');
                $table->string('type');
                $table->string('driver');
                $table->string('image');
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
        Schema::dropIfExists('boats');
    }
}
