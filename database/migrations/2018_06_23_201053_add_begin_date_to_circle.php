<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBeginDateToCircle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('circles', 'begin') == false) {
            Schema::table('circles', function ($table) {
                $table->date('begin')->after('limit')->default(today());
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
        Schema::table('circles', function ($table) {
            $table->dropColumn([
                'begin'
            ]);
        });
    }
}
