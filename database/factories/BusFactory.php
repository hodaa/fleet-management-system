<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Bus;

$factory->define(Bus::class, function (Faker $faker) {
    return [
        'bus_no'=>$faker->text(5),
        'seat_no' => $faker->unique()->name,
        'line_id' => 1,


    ];
});
