<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiveDayPlanningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('dive_day_plannings')) {
            Schema::create('dive_day_plannings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('merchant_key');
                $table->integer('dive_center_id');
                $table->char('title');
                $table->boolean('night_dive');
                $table->string('dive_number');
                $table->date('date');
                $table->char('start_time');
                $table->char('end_time');
                $table->text('combinations');
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
        Schema::dropIfExists('dive_day_plannings');
    }
}
