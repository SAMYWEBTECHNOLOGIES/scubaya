<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('merchants')) {
            Schema::create('merchants', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key')->comment('This key corresponds to the user tables primary key');
                $table->string('group_id')->nullable();
                $table->string('instructor_ids')->nullable();
                $table->string('last_login_info')->nullable();
                $table->string('company_name')->nullable();
                $table->string('vat_number')->nullable();
                $table->string('chamber_of_commerce')->nullable();
                $table->string('street')->nullable();
                $table->string('town')->nullable();
                $table->string('region')->nullable();
                $table->string('country')->nullable();
                $table->string('postcode')->nullable();
                $table->string('telephone')->nullable();
                $table->string('longitude')->nullable();
                $table->string('latitude')->nullable();
                $table->rememberToken();
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
        Schema::dropIfExists('merchants');
    }
}
