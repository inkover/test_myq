<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRobotCleaningSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robot_cleaning_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->text('map')->nullable();
            $table->integer('start_x')->nullable();
            $table->integer('start_y')->nullable();
            $table->string('start_facing')->nullable();
            $table->string('start_battery')->nullable();
            $table->text('commands')->nullable();
            $table->integer('x')->nullable();
            $table->integer('y')->nullable();
            $table->string('facing')->nullable();
            $table->integer('battery')->nullable();
            $table->timestamps();
            $table->unique(['token']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('robot_cleaning_sessions');
    }
}
