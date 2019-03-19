<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   if(!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('UID');
                $table->string('first_name',400)->nullable();
                $table->string('last_name',400)->nullable();
                $table->string('email',400);
                $table->string('password')->nullable();
                //$table->tinyInteger('role_id');
                $table->boolean('is_admin');
                $table->boolean('is_merchant');
                $table->boolean('is_user');
                $table->boolean('is_merchant_user');
                $table->string('account_status')->nullable();
                $table->boolean('confirmed')->default(0)->nullable();
                $table->string('confirmation_code')->nullable();
                $table->string('token')->nullable();
                $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
