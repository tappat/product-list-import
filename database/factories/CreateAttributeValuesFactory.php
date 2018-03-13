<?php

use App\AttributeValue;
use Faker\Generator as Faker;

$factory->define(AttributeValue::class, function (Faker $faker) {
    return [
      'name' => $faker->word(1)
    ];
});
