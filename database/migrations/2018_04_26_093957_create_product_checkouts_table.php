<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('product_checkouts')) {
            Schema::create('product_checkouts', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_key');
                $table->integer('product_id');
                $table->integer('quantity');
                $table->double('subtotal');
                $table->string('status');
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
        Schema::dropIfExists('product_checkouts');
    }
}
