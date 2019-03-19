<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLogDivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('user_log_dives')) {
            Schema::create('user_log_dives', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('log_name')->nullable();
                $table->date('log_date')->nullable();
                $table->tinyInteger('training_dive')->default(0);
                $table->char('dive_mode', 100);
                $table->char('dive_center', 100);
                $table->string('notes')->nullable();
                $table->string('buddy', 100);
                $table->integer('altitude')->nullable();
                $table->double('latitude');
                $table->double('longitude');
                $table->char('day_dive', 50);
                $table->char('night_dive', 50);
                $table->char('dive_type', 50);
                $table->decimal('temperature', 8, 2)->nullable();
                $table->char('waves', 50)->nullable();
                $table->char('current', 50)->nullable();
                $table->char('visibility', 50)->nullable();
                $table->string('surface_temperature')->default(0);
                $table->string('bottom_temperature')->default(0);
                $table->string('water_time')->nullable();
                $table->string('total_time')->nullable();
                $table->string('pressure_in_enter_exit_water_time')->nullable();
                $table->string('pressure')->nullable();
                $table->float('tank_capacity')->default(0.0);
                $table->char('tank_type', 20);
                $table->integer('oxygen')->nullable();
                $table->double('average_depth')->nullable();
                $table->double('maximum_depth')->nullable();
                $table->string('surface_interval')->nullable();
                $table->string('dive_site')->nullable();
                $table->string('dive_number')->nullable();
                $table->string('equipments')->nullable();
                $table->char('verify_my_dive_status',30)->nullable();

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
        Schema::dropIfExists('user_log_dives');
    }
}
