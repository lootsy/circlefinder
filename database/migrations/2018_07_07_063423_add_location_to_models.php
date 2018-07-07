<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationToModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'city_id') == false) {
            Schema::table('users', function ($table) {
                $table->integer('city_id')->after('email')->unsigned()->nullable();
            });
        }

        if (Schema::hasColumn('circles', 'city_id') == false) {
            Schema::table('circles', function ($table) {
                $table->integer('city_id')->after('begin')->unsigned()->nullable();
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
        Schema::table('users', function ($table) {
            $table->dropColumn([
                'city_id',
            ]);
        });

        Schema::table('circles', function ($table) {
            $table->dropColumn([
                'city_id',
            ]);
        });        
    }
}
