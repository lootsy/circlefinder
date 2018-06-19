<?php

use Faker\Generator as Faker;

$factory->define(\App\Circle::class, function (Faker $faker) {
    return [
        'user_id' => 0,
        'type' => $faker->randomElement(['f2f', 'virtual', 'both']),
        'title' =>  $faker->catchPhrase
    ];
});
