<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\BusLine;

$factory->define(BusLine::class, function (Faker $faker) {
    return [
        'bus_no'=>$faker->text(5),
        'seat_no' => $faker->unique()->name,
        'line_id' => 1,


    ];
});
