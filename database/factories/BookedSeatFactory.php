<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\BookedSeat;

$factory->define(BookedSeat::class, function (Faker $faker) {
    return [
        'bus_id'=>1,
        'pickup_id'=>1,
        'destination_id'=>2,
        'user_id' =>1,
    ];
});
