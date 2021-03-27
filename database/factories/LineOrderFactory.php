<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Station;
use App\Models\Line;
use App\Models\LineOrder;

$factory->define(LineOrder::class, function (Faker $faker) {
    return [
        'line_id' =>  factory(Line::class)->create()->id,
        'station_id' => factory(Station::class)->create()->id,
        'next_station' => null,
        'order'=> 1
    ];
});
