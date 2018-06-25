<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local'))
        {
            $this->call(UsersTableSeeder::class);
            $this->call(RolesTableSeeder::class);
            $this->call(CirclesTableSeeder::class);
            $this->call(LanguagesTableSeeder::class);
        }
    }
}
