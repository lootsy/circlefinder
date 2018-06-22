<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Config;

$factory->define(\App\Circle::class, function (Faker $faker) {
    return [
        'user_id' => \App\User::inRandomOrder()->get()->first(),
        'type' => $faker->randomElement(Config::get('circle.defaults.types')),
        'title' =>  $faker->catchPhrase,
        'completed' => false,
        'limit' => 5,
        'description' => $faker->text
    ];
});
