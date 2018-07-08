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
        if (Schema::hasColumn('users', 'city_code') == false) {
            Schema::table('users', function ($table) {
                $table->string('city_code')->after('email')->nullable();
            });
        }

        if (Schema::hasColumn('circles', 'city_code') == false) {
            Schema::table('circles', function ($table) {
                $table->string('city_code')->after('begin')->nullable();
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
                'city_code',
            ]);
        });

        Schema::table('circles', function ($table) {
            $table->dropColumn([
                'city_code',
            ]);
        });        
    }
}
