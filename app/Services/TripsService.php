<?php


namespace App\Services;

use App\Models\BusLine;
use App\Models\Station;
use DB;

class TripsService
{
    /**
     * @param $stationName
     * @return mixed
     */
    public function getStationId(string $stationName) :?int
    {
        return optional(Station::where('name', $stationName)->select('id')->first())->id;
    }


    /**
     * @param $start_id
     * @param $end_id
     * @return mixed
     */
    public function getAvailableSeats($start_id, $end_id)
    {
        return BusLine::where(function ($query) use ($start_id, $end_id) {
            $query->where('line_id', $this->getLineId($start_id, $end_id))
                ->join('lines', 'lines.id', 'bus_lines.line_id');
        })->join('lines', 'bus_lines.line_id', 'lines.id')
            ->where(function ($query) use ($start_id, $end_id) {
                $query->whereNull('pickup_id')->whereNull('destination_id');
            })->orWhere(function ($query) use ($start_id, $end_id) {
                $query->where("destination_id", '!=', $end_id);
            })->get();
    }

    /**
     * @param $start_id
     * @param $end_id
     * @return int
     */
    public function getLineId($start_id, $end_id) :?int
    {
        return optional(DB::table('line_orders as l1')
                ->join('line_orders as l2', 'l1.line_id', 'l2.line_id')
                ->where('l1.station_id', $start_id)->where('l2.next_station', $end_id)
                ->orderBy('l1.order')->first())->line_id;
    }

    /**
     * @param $id
     * @param $user_id
     * @param $pickup_id
     * @param $destination_id
     * @return mixed
     */
    public function bookSeat($id, $user_id, $pickup_id, $destination_id)
    {
        $seat = BusLine::find($id);
        $seat->update([
            'user_id' => $user_id,
            'pickup_id'=> $pickup_id,
            'destination_id'=> $destination_id
        ]);
        return $seat;
    }
}
