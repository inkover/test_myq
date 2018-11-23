<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRobotCleaningActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robot_cleaning_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('session_id');
            $table->string('command');
            $table->integer('start_x');
            $table->integer('start_y');
            $table->string('start_facing');
            $table->integer('start_battery');
            $table->integer('finish_x');
            $table->integer('finish_y');
            $table->string('finish_facing');
            $table->integer('finish_battery');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('robot_cleaning_actions');
    }
}
