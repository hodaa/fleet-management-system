<?php


namespace App\Services;

use App\Models\BookedSeat;
use App\Models\Bus;
use App\Models\Station;
use DB;
use Illuminate\Support\Carbon;

class TripsService
{
    /**
     * @param $stationName
     * @return mixed
     */
    public function getStationId($stationName) :?int
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
        return Bus::where(function ($query) use ($start_id, $end_id) {
            $query->where('line_id', $this->getLineId($start_id, $end_id))
                ->join('lines', 'lines.id', 'buses.line_id');
        })->join('lines', 'buses.line_id', 'lines.id')
            ->leftJoin('booked_seats', 'buses.id', '=', 'booked_seats.bus_id')
           ->where(function ($query) use ($start_id, $end_id) {
                $query->where("booked_seats.destination_id", '!=', $end_id)
                   ->whereRaw("not (booked_seats.destination_id = $end_id AND booked_seats.pickup_id = $start_id)");
           })->orWhere(function ($query) use ($start_id, $end_id) {
               $query->whereNull('booked_seats.pickup_id')->whereNull('booked_seats.destination_id');
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
                ->where('l1.station_id', $start_id)
                ->groupBy('l1.line_id')
                ->havingRaw('min(l1.`order`) < max(l2.`order`)')
                ->select('l1.line_id')
                ->first())->line_id;
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
        return BookedSeat::create([
             'bus_id'=> $id,
             'user_id' => $user_id,
             'pickup_id'=> $pickup_id,
             'destination_id'=> $destination_id,
             'created_at' => Carbon::now(),
             'updated_at'=> Carbon::now()
         ]);
    }
    /**
     * @param $line_id
     * @param $seat_id
     * @return int|null
     */
    public function getBusId($line_id, $seat_id) :?int
    {
        return optional(Bus::where(["seat_no" => $seat_id, "line_id"=> $line_id])->first())->id;
    }
}
