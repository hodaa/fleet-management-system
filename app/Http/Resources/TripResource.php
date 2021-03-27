<?php

namespace App\Http\Resources;

use App\Models\LineOrder;
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
        $pickup = $request->start;
        $destination = $request->end;

        if ($this->pickup_id !== null && $this->destination_id !== null) {
            $start_id = Station::where('name', $request->start)->select('id')->first()->id;
            $end_id = Station::where('name', $request->end)->select('id')->first()->id;

//            $start_order = \DB::table('line_order')->where('station_id', $start_id)->where('line_id', $this->line_id)->select('order')->pluck('order')->first();
//            $booked_start_order = \DB::table('line_order')->where('station_id', $this->pickup_id)->where('line_id', $this->line_id)->select('order')->pluck('order')->first();

            $end_order = LineOrder::where('station_id', $end_id)->where('line_id', $this->line_id)->select('order')->pluck('order')->first();
            $booked_end_order = LineOrder::where('station_id', $this->destination_id)->where('line_id', $this->line_id)->select('order')->pluck('order')->first();

            if ($booked_end_order <= $end_order) {
                $pickup = optional($this->destination)->name?? $request->end;
            }
        }

        return [
            'id'=> $this->id,
            'bus_no' => $this->bus_no,
            'seat_no' => $this->seat_no,
            'pickup_station' => $pickup,
            'destination_station' => $destination
        ];
    }
}
