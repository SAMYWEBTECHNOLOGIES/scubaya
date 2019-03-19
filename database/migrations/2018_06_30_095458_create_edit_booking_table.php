<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('edit_booking')) {
            Schema::create('edit_booking', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('booking_id')->comment('This is id of booking request table.');
                $table->string('table_name');
                $table->text('params');
                $table->string('status', 10)->default('pending');
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
        Schema::dropIfExists('edit_booking');
    }
}
