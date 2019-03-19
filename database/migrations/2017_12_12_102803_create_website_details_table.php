<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('website_details')) {
            Schema::create('website_details', function (Blueprint $table) {
                $table->increments('id');
                $table->string('website_type')->comment('This field indicates website type whether it is shop or dive center etc.');
                $table->integer('website_id')->comment('This is the id of website');
                $table->integer('merchant_key');
                $table->string('first_name');
                $table->string('last_name');
                $table->bigInteger('phone_no')->nullable();
                $table->string('email');
                $table->text('address')->nullable();
                $table->string('street')->nullable();
                $table->string('house_no')->nullable();
                $table->string('house_no_extension')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('company_name')->nullable();
                $table->string('legal_id_no')->nullable();
                $table->string('vat_no')->nullable();
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
        Schema::dropIfExists('website_details');
    }
}
