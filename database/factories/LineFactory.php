<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Station;
use App\Models\Line;

$factory->define(Line::class, function (Faker $faker) {
    return [
        'end_station_id' => factory(Station::class)->create()->id,
        'start_station_id' => factory(Station::class)->create()->id,

    ];
});
