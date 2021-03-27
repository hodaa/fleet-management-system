<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Station;

$factory->define(Station::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
    ];
});
