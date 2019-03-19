<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key');
                $table->integer('shop_id');
                $table->string('dive_center')->nullable();
                $table->string('course_name')->nullable();
                $table->string('image')->nullable();
                $table->string('gallery')->nullable();
                $table->string('affiliates')->nullable();
                $table->string('instructors')->nullable();
                $table->string('boats')->nullable();
                $table->string('course_start_date')->nullable();
                $table->string('course_end_date')->nullable();
                $table->string('course_days')->nullable();
                $table->string('course_pricing')->nullable();
                $table->string('location')->nullable();
                $table->string('products')->nullable();
                $table->text('description')->nullable();
                $table->text('cancellation_detail')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
