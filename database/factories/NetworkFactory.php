<?php

use App\Network;
use Faker\Generator as Faker;

$factory->define(Network::class, function (Faker $faker) {
    return [
      'name' => $faker->word(1),
      'url' => $faker->url
    ];
});
