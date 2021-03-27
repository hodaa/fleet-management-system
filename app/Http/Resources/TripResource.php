<?php

namespace App\Http\Resources;

use App\Models\Station;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'bus_no' => $this->bus_no,
            'seat_no' => $this->seat_no,
            'pickup_station' => $this->pickup_id ? Station::find($this->destination_id)->name :$request->start,
            'destination_station' => $request->end,
        ];
    }
}
