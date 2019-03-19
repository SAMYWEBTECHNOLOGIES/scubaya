<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMerchantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('merchant_details')) {
            Schema::create('merchant_details', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_primary_id');
                $table->string('company_type')->nullable();
                $table->string('company_id')->nullable();
                $table->string('full_name')->nullable();
                $table->string('dob')->nullable();
                $table->text('address')->nullable();
                $table->string('city')->nullable();
                $table->string('postal_code')->nullable();
                $table->string('rating')->nullable();
                $table->string('screening')->nullable();
                $table->boolean('contact_module')->comment('This is module which will be shown on dive center page.');
                $table->string('status')->nullable();
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
        Schema::dropIfExists('merchant_details');
    }
}
