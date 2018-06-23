<?php

use Faker\Generator as Faker;

$factory->define(\App\Language::class, function (Faker $faker) {    
    $list = \App\Language::getListOfLanguages();

    $language_code = $faker->randomElement(array_keys($list));

    return [
        'title' => $list[$language_code],
        'code' => $language_code
    ];
});
