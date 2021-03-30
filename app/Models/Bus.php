<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    /**
     * @return mixed
     */
    public function line()
    {
        return $this->hasOne(Line::class);
    }

    /**
     * @return HasMany
     */
    public function Seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
    public function notAvailableSeats($line_id, $start_id, $end_id)
    {

        return $this->belongsToMany(BookedSeat::class, 'booked_seats')
            ->where('line_id', $line_id)
            ->where('booked_seats.pickup_id', '<=', $start_id)
            ->where('booked_seats.destination_id', '>', $end_id);
    }
}
