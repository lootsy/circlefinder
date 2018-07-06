<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTimeTableForJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_slots', function ($table) {
            $table->json('monday')->change();
            $table->json('tuesday')->change();
            $table->json('wednesday')->change();
            $table->json('thursday')->change();
            $table->json('friday')->change();
            $table->json('saturday')->change();
            $table->json('sunday')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_slots', function ($table) {
            $table->string('monday')->change();
            $table->string('tuesday')->change();
            $table->string('wednesday')->change();
            $table->string('thursday')->change();
            $table->string('friday')->change();
            $table->string('saturday')->change();
            $table->string('sunday')->change();
        });
    }
}
