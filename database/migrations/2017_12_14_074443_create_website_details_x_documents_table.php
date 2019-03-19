<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteDetailsXDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('website_details_x_documents')) {
            Schema::create('website_details_x_documents', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('website_id')->comment('This is primary key of website or sub account like shop, dive center etc.');
                $table->integer('website_detail_id')->comment('This is the primary key of sub account or website verification details table.');
                $table->longText('passport_or_id');
                $table->longText('legal_doc');
                $table->longText('bank_details');
                $table->string('status');
                $table->boolean('is_active')->default('1');
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
        Schema::dropIfExists('website_details_x_documents');
    }
}
