<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantUsersXRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('merchant_users_x_roles')) {
            Schema::create('merchant_users_x_roles', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('merchant_id');
                $table->integer('user_id');
                $table->text('group_id');
                $table->text('sub_account_rights')->nullable();
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
        Schema::dropIfExists('merchant_users_x_roles');
    }
}
