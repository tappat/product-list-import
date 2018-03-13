<?php

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'external_id' => $faker->randomNumber(4),
        'name' => $faker->sentence(3)
    ];
});
