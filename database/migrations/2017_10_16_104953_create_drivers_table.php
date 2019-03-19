<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('boat_drivers')) {
            Schema::create('boat_drivers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_primary_id');
                $table->string('name');
                $table->string('email')->unique();
                $table->bigInteger('contact_number');
                $table->string('document');
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
        Schema::dropIfExists('boat_drivers');
    }
}
