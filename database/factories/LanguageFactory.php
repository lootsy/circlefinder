<?php

use Faker\Generator as Faker;

$factory->define(\App\Language::class, function (Faker $faker) {
    $languageCode = $faker->languageCode;

    return [
        'title' => 'Lang_' . $languageCode,
        'code' => $languageCode
    ];
});
