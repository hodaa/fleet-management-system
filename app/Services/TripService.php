<?php


namespace App\Services;

use App\Models\BookedSeat;
use App\Models\Bus;
use App\Models\LineOrder;
use App\Models\Station;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TripService
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
        $result=[];
        $tips= $this->getLines($start_id, $end_id);
//        $availableSeats = new Collection;
        foreach ($tips as $trip) {
            $availableSeats = $this->getNotAvailableSeat($trip->line->id, $start_id, $end_id);
//            $availableSeats->push($availableSeat);
//            $validSeats = $line->bus->seats->diff($notAvailableSeats);
        }
//        dd($availableSeats);
        return $availableSeats;

    }

    /**
     * @param $start_id
     * @param $end_id
     * @return mixed
     */
    public function getLines($start_id, $end_id)
    {
        return LineOrder::join('line_orders as l2', 'line_orders.line_id', 'l2.line_id')
                ->where('line_orders.station_id', $start_id)
                ->where('l2.next_station', $end_id)
                ->select('line_orders.line_id')
                ->get();
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
     * @param $start_id
     * @param $end_id
     * @return mixed
     */
    public function getNotAvailableSeat($line_id, $start_id, $end_id)
    {
        return  BookedSeat::with('bus')->join('buses', 'booked_seats.bus_id', 'buses.id')
            ->where('pickup_id', '>=', $start_id)
            ->where('destination_id', '<', $end_id)
            ->where('buses.line_id', $line_id)
            ->select('*')
            ->get();
    }
}
