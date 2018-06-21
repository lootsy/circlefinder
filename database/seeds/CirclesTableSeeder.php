<?php

use Illuminate\Database\Seeder;

class CirclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Circle::class, 20)->create();
    }
}
