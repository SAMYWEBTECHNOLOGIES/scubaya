<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('destinations')) {
            Schema::create('destinations', function (Blueprint $table) {
                $table->increments('id');
                $table->tinyInteger('active')->comment('the destination is active or not as 1 or 0');
                $table->tinyInteger('is_sub_destination')->comment('the sub destination is active or not as 1 or 0');
                $table->string('name')->nullable();
                $table->string('sub_name')->nullable();
                $table->string('location')->nullable();
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
                $table->string('country')->nullable();
                $table->string('is_subdestination_of')->nullable();
                $table->string('geographical_area')->nullable();
                $table->string('language_spoken')->nullable();
                $table->string('hdi_rank')->nullable();
                $table->string('region')->nullable();
                $table->tinyInteger('water_temperature')->comment('whether to show the water temperature or not as 0 or 1');
                $table->tinyInteger('weather')->comment('whether to show the weather or not as 0 or 1');
                $table->string('image')->nullable();
                $table->string('images')->nullable();
                $table->string('voltage')->nullable();
                $table->string('accepted_currency')->nullable();
                $table->string('country_currency')->nullable();
                $table->text('short_description')->nullable();
                $table->text('long_description')->nullable();
                $table->text('dive_description')->nullable();
                $table->text('tourist_description')->nullable();
                $table->string('time_zone')->nullable();
                $table->string('rs_floor')->nullable();
                $table->string('macro')->nullable();
                $table->string('pelagic')->nullable();
                $table->string('wreck')->nullable();
                $table->text('season')->nullable();
                $table->text('exposure_season')->nullable();
                $table->text('rain_season')->nullable();
                $table->string('population')->nullable();
                $table->string('religion')->nullable();
                $table->string('capital_wikipedia')->nullable();
                $table->text('map_decompression_chambers')->nullable();
                $table->string('climate', 15)->nullable();
                $table->string('phone_code')->nullable();
                $table->string('water_temp', 2000)->nullable();
                $table->string('rain_fall_temp', 2000)->nullable();
                $table->string('min_air_temp', 2000)->nullable();
                $table->string('max_air_temp', 2000)->nullable();
                $table->text('destination_tips')->nullable();
                $table->string('visa_countries')->nullable();
                $table->tinyInteger('is_destination_popular')->default(0);
                $table->string('tipping')->nullable();
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
        Schema::dropIfExists('destinations');
    }
}
