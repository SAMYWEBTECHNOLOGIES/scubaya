<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManageDiveCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('manage_dive_centers')) {
            Schema::create('manage_dive_centers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key');
                $table->string('name');
                $table->string('address');
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->integer('zipcode')->nullable();
                $table->double('latitude')->nullable();
                $table->double('longitude')->nullable();
                $table->string('facebook_url',2083)->nullable();
                $table->string('twitter_url',2083)->nullable();
                $table->string('instagram_url',2083)->nullable();
                $table->string('image');
                $table->string('gallery')->nullable();
                $table->text('activities')->nullable();
                $table->text('non_diving_activities')->nullable();
                $table->text('facilities')->nullable();
                $table->text('specialities')->nullable();
                $table->text('member_affiliations')->nullable();
                $table->text('affiliations')->nullable();
                $table->text('language_spoken')->nullable();
                $table->text('infrastructure')->nullable();
                $table->text('payment_methods')->nullable();
                $table->string('required_documents', 5000)->nullable();
                $table->string('cancellation_policy', 2000)->nullable();
                $table->string('distance_from_sea')->nullable();
                $table->text('groups')->nullable();
                $table->text('opening_days')->nullable();
                $table->text('short_description')->nullable();
                $table->text('long_description')->nullable();
                $table->text('read_before_you_go')->nullable();
                $table->text('gears')->nullable();
                $table->string('filling_station')->nullable();
                $table->boolean('nitrox')->nullable();
                $table->string('discovery_dives')->nullable();
                $table->string('fun_dives')->nullable();
                $table->string('other_dives')->nullable();
                $table->text('season')->nullable();
                $table->tinyInteger('is_center_popular')->default(0);
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
        Schema::dropIfExists('manage_dive_centers');
    }
}
