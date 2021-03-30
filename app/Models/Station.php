<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{

    public static function trips($userDirection)
    {
        $to = $userDirection->to;
        return Trip::select(['id', 'bus_id'])
            ->join('station_trip', 'trips.id', '=', 'station_trip.trip_id')
            ->where('station_trip.from_station_id', '=', $userDirection->from)
            ->whereExists(function ($query) use ($to) {
                $query->select('to_station_id')
                    ->from('station_trip')
                    ->whereColumn('trip_id', '=', 'trips.id')
                    ->where('to_station_id', '=', $to);
            });
    }
}
