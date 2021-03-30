<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }


    public function notAvailableSeats($userDirection) {
        return $this->belongsToMany(Seat::class,'users_trips')
            ->where('users_trips.trip_id', '=', $this->id)
            ->where('users_trips.from_station', '<=', $userDirection->from)
            ->where('users_trips.to_station', '>', $userDirection->from);
    }

}
