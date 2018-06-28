<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('membership_id')->unsigned();
            $table->time('time');
            $table->tinyInteger('monday');
            $table->tinyInteger('tuesday');
            $table->tinyInteger('wednesday');
            $table->tinyInteger('thursday');
            $table->tinyInteger('friday');
            $table->tinyInteger('saturday');
            $table->tinyInteger('sunday');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('times');
    }
}
