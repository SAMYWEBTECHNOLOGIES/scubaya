<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiveSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('dive_site')) {
            Schema::create('dive_site', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('is_active')->nullable();
                $table->boolean('need_a_boat')->nullable();
                $table->string('name')->nullable();
                $table->string('max_depth')->nullable();
                $table->string('avg_depth')->nullable();
                $table->string('diver_level', 1500)->nullable();
                $table->string('current')->nullable();
                $table->string('max_visibility')->nullable();
                $table->string('avg_visibility')->nullable();
                $table->string('type')->nullable();
                $table->string('image')->nullable();
                $table->string('country')->nullable();
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
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
        Schema::dropIfExists('dive_site');
    }
}
