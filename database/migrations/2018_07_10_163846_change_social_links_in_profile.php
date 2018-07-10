<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSocialLinksInProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->renameColumn('facebook_profile_url', 'facebook_profile');
            $table->renameColumn('twitter_profile_url', 'twitter_profile');
            $table->renameColumn('linkedin_profile_url', 'linkedin_profile');
            $table->renameColumn('yammer_profile_url', 'yammer_profile');
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
            $table->renameColumn('facebook_profile', 'facebook_profile_url');
            $table->renameColumn('twitter_profile', 'twitter_profile_url');
            $table->renameColumn('yammer_profile', 'yammer_profile_url');
            $table->renameColumn('linkedin_profile', 'linkedin_profile_url');
        });
    }
}
