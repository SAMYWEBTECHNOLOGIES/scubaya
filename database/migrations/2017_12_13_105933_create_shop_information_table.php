<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('shop_information')) {
            Schema::create('shop_information', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key')->comment('This is the id of user whose role is merchant.');
                $table->string('name');
                $table->string('profile_image');
                $table->text('address');
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->string('zipcode')->nullable();
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
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
        Schema::dropIfExists('shop_information');
    }
}
