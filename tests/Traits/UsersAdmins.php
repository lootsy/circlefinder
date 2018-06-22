<?php

namespace Tests\Traits;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Config;

trait UsersAdmins
{
    private function fetchAdmin()
    {
        return factory(\App\Admin::class)->create();
    }

    private function fetchUser($id = null)
    {
        if($id == null)
        {
            return factory(\App\User::class)->create();
        }
        else
        {
            return \App\User::find($id);
        }
    }

    private function fetchFaker()
    {
        return Faker::create();
    }

    private function fetchRole($id = null)
    {
        if($id == null)
        {
            return factory(\App\Role::class)->create();
        }
        else
        {
            return \App\Role::find($id);
        }
    }

    public function fetchModerator()
    {
        $user = $this->fetchUser();
        
        $role = \App\Role::where('name', 'moderator')->first();
        
        $user->roles()->attach($role);

        return $user;
    }

    private function fetchLanguage($id = null)
    {
        if($id == null)
        {
            return factory(\App\Language::class)->create();
        }
        else
        {
            return \App\Language::find($id);
        }
    }

    private function fetchCircle($owner)
    {
        $faker = $this->fetchFaker();

        $data = [
            'type' => $faker->randomElement(['f2f', 'virtual', 'both']),
            'title' =>  $faker->catchPhrase,
            'limit' => Config::get('circle.defaults.limit')
        ];

        return $owner->circles()->create($data);
    }
}