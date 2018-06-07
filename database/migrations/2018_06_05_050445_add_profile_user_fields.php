<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->text('about')->after('email')->nullable();
            $table->text('language')->after('about')->nullable();
            $table->text('location')->after('language')->nullable();
            $table->string('facebook_profile_url')->after('about')->nullable();
            $table->string('twitter_profile_url')->after('about')->nullable();
            $table->string('linkedin_profile_url')->after('about')->nullable();
            $table->string('yammer_profile_url')->after('about')->nullable();
        });
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
                'about', 
                'language', 
                'location', 
                'facebook_profile_url', 
                'twitter_profile_url',
                'linkedin_profile_url',
                'yammer_profile_url',
            ]);
        });
    }
}
