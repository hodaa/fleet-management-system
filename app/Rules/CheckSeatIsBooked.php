<?php

namespace App\Rules;

use App\Models\BookedSeat;
use App\Services\TripService;
use Illuminate\Contracts\Validation\Rule;

class CheckSeatIsBooked implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $pickup_id =app(TripService::class)->getStationId(request()->pickup_point);
        $destination_id = app(TripService::class)->getStationId(request()->destination_point);
        $line_id= app(TripService::class)->getLineId($pickup_id, $destination_id);
        $bus_id = app(TripService::class)->getBusId($line_id, request()->seat_id);

       return ! BookedSeat::where(['bus_id' => $bus_id,'pickup_id' => $pickup_id,'destination_id' => $destination_id])->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Seat is already booked.';
    }
}
