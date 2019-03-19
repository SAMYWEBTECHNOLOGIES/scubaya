<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MerchantMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('merchant_menus')) {
            Schema::create('merchant_menus', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('as');
                $table->tinyInteger('parent_id')->default(0);
                $table->string('slug')->nullable();
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
        //
    }
}
