<?php

use App\Advertiser;
use Faker\Generator as Faker;

$factory->define(Advertiser::class, function (Faker $faker) {
    return [
        'name' => $faker->word(1),
        'url' => $faker->url
    ];
});
