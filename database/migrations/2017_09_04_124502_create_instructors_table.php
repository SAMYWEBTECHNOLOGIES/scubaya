<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('instructors')) {
            Schema::create('instructors', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('instructor_key');
                $table->string('merchant_ids')->nullable();
                $table->integer('dive_center_id');
                $table->date('dob');
                $table->string('nationality');
                $table->tinyInteger('availability')->default(1);
                $table->string('certifications')->nullable();
                $table->smallInteger('years_experience')->nullable();
                $table->smallInteger('total_dives')->nullable();
                $table->string('spoken_languages');
                $table->string('facebook')->nullable();
                $table->string('twitter')->nullable();
                $table->string('instagram')->nullable();
                $table->bigInteger('phone');
                $table->string('own_website')->nullable();
                $table->string('short_story');
                $table->string('pricing');
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
        Schema::dropIfExists('instructors');
    }
}
