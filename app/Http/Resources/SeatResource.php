<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
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
            'pickup_station' => optional($this->pickup)->name,
            'destination_station' => optional($this->destination)->name,
            'user'=> $this->user->name
        ];
    }
}
