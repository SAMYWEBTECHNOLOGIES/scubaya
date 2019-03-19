<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiveCenterCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('dive_center_checkouts')) {
            Schema::create('dive_center_checkouts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('user_key');
                $table->string('course_id');
                $table->integer('no_of_people');
                $table->text('divers')->nullable();
                $table->double('subtotal')->nullable();
                $table->string('status')->comment('whether user has successfully paid or not');
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
        Schema::dropIfExists('dive_center_checkouts');
    }
}
