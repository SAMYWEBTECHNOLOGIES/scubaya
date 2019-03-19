<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPersonalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_personal_informations')) {
            Schema::create('user_personal_informations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_key');
                $table->string('gender', 400);
                $table->string('dob', 400);
                $table->string('user_name',400)->nullable();
                $table->string('first_name',400)->nullable();
                $table->string('last_name',400)->nullable();
                $table->string('nationality', 400);
                $table->string('email',400)->nullable();
                $table->string('phone',400)->nullable();
                $table->string('mobile',400)->nullable();
                $table->string('street',400)->nullable();
                $table->string('house_number',400)->nullable();
                $table->string('house_number_extension',400)->nullable();
                $table->string('postal_code', 400);
                $table->string('city', 400);
                $table->string('country', 400);
                $table->string('image', 400);
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
        Schema::dropIfExists('user_personal_informations');
    }
}
