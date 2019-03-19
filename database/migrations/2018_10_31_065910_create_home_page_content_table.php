<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomepageContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('home_page_contents')) {
            Schema::create('home_page_contents', function (Blueprint $table) {
                $table->increments('id');
                $table->text('subscription_content')->nullable();
                $table->text('blog_content')->nullable();
                $table->text('features_content')->nullable();
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
         Schema::dropIfExists('home_page_contents');
    }
}
