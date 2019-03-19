<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsXMerchantsDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('merchants_x_merchants_documents')) {
            Schema::create('merchants_x_merchants_documents', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_detail_id');
                $table->integer('merchant_primary_id');
                $table->longText('passport_or_id');
                $table->longText('company_legal_doc');
                $table->longText('company_bank_details');
                $table->char('status',100);
                $table->tinyInteger('upload_hits');
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
        Schema::dropIfExists('merchants_x_merchants_documents');
    }
}
