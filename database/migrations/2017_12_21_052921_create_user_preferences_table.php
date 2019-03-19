<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_preferences')) {
            Schema::create('user_preferences', function (Blueprint $table) {
                $table->increments('id');
                $table->char('user_key', 100);
                $table->char('distance', 100);
                $table->char('weight', 100);
                $table->char('pressure', 100);
                $table->char('temperature', 100);
                $table->char('volume', 100);
                $table->char('date_format', 100);
                $table->char('time_format', 100);
                $table->char('coordinates_format', 100);
                $table->char('language', 100)->nullable();
                $table->char('currency', 100);
                $table->char('departure_airport', 100);
                $table->tinyInteger('newsletter')->nullable();
                $table->tinyInteger('partners_related_offers')->nullable();
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
        Schema::dropIfExists('user_preferences');
    }
}
