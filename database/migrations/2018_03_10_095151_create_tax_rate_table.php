<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('tax_rate')) {
            Schema::create('tax_rate', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key');
                $table->string('title');
                $table->string('country');
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('region')->nullable();
                $table->string('zipcode')->nullable();
                $table->string('rate');
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
        Schema::dropIfExists('tax_rate');
    }
}
