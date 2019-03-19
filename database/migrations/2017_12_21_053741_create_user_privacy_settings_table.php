<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPrivacySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_privacy_settings')) {
            Schema::create('user_privacy_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->char('user_key', 100);
                $table->char('user_profile', 100);
                $table->char('diver_profile', 100);
                $table->char('dive_log', 100);
                $table->char('my_reviews', 100);
                $table->char('contact_details', 100);
                $table->char('photos', 100);
                $table->char('friends', 100);
                $table->char('emergency_info', 100);
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
        Schema::dropIfExists('user_privacy_settings');
    }
}
