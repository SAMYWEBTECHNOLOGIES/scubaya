<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomPricingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('room_pricing')) {
            Schema::create('room_pricing', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_primary_id');
                $table->string('tariff_title');
                $table->text('tariff_description')->nullable();
                $table->integer('room_id');
                $table->longText('additional_tariff_data')->comment('This data is prepared according to the tariff mode selected by merchant.');
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
        Schema::dropIfExists('room_pricing');
    }
}
