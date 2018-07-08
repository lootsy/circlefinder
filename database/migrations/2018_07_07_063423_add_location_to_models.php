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
        if (Schema::hasColumn('users', 'location') == false) {
            Schema::table('users', function ($table) {
                $table->string('location')->after('email')->nullable();
                $table->string('timezone')->after('email')->nullable();
            });
        }

        if (Schema::hasColumn('circles', 'location') == false) {
            Schema::table('circles', function ($table) {
                $table->string('location')->after('begin')->nullable();
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
                'location',
                'timezone'
            ]);
        });

        Schema::table('circles', function ($table) {
            $table->dropColumn([
                'location',
            ]);
        });        
    }
}
