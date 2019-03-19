<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarineLivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            if(!Schema::hasTable('marine_lives')) {
            Schema::create('marine_lives', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('active');
                $table->string('common_name');
                $table->string('scientific_name');
                $table->string('main_image')->nullable();
                $table->string('max_images')->nullable();
                $table->text('description')->nullable();
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
        Schema::dropIfExists('marine_lives');
    }
}
