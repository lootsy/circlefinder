<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModeratorRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = \App\Role::withTrashed()->where('name', 'moderator');

        if($roles->count() > 0)
        {
            return;
        }

        $role = new \App\Role;
        $role->title = 'Moderator';
        $role->name = 'moderator';
        $role->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roles = \App\Role::withTrashed()->where('name', 'moderator');
        
        if($roles->count() > 0)
        {
            $roles->first()->forceDelete();
        }
    }
}
