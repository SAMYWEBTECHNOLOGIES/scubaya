<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_key');
                $table->integer('shop_id');
                $table->string('title')->nullable();
                $table->string('sku')->nullable();
                $table->double('weight')->nullable();
                $table->boolean('product_status')->nullable();
                $table->double('tax')->nullable();
                $table->string('visibility')->nullable();
                $table->string('manufacturer')->nullable();
                $table->string('color')->nullable();
                $table->string('availability_from')->nullable();
                $table->string('availability_to')->nullable();
                $table->double('price')->nullable();
                $table->boolean('incl_in_course')->nullable();
                $table->integer('no_of_products')->nullable();
                $table->string('product_type')->nullable();
                $table->text('sub_accounts')->nullable();
                $table->string('category')->nullable();
                $table->string('short_description')->nullable();
                $table->text('description')->nullable();
                $table->string('product_image')->nullable();
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
        Schema::dropIfExists('products');
    }
}
