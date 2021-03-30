<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripBookRequest;
use App\Http\Requests\TripsRequest;
use App\Models\Station;
use App\Services\TripService;
use App\Http\Resources\SeatResource;
use App\Http\Resources\TripResource;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * @var TripsService
     */
    private $tripService;

    /**
     * TripsController constructor.
     * @param TripsService $tripService
     */
    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * @param TripsRequest $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $start_id = $this->tripService->getStationId($request->input('start'));
        $end_id = $this->tripService->getStationId($request->input('end'));
        $seats = $this->tripService->getAvailableSeats($start_id, $end_id);

        return response()->json(['data' => TripResource::collection(collect($seats))]);
    }

    public function book(TripBookRequest $request)
    {
        $user_id = auth()->user()->id;
        try {
            $seat =$this->tripService->bookSeat($request->bus_id, $user_id, $request->pickup_id, $request->destination_id);
        } catch (\Exception $exception) {
            return response()->json(["message"=>"Something went wrong"], 403);
        }

        return response()->json(["message"=>"Your seat booked successfully","data"=> new SeatResource($seat) ]);
    }

    /**
     * @param $line_id
     */

}
