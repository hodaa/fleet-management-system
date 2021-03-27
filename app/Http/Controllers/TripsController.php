<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripBookRequest;
use App\Http\Requests\TripsRequest;
use App\Services\TripsService;
use App\Http\Resources\SeatResource;
use App\Http\Resources\TripResource;

class TripsController extends Controller
{
    /**
     * @var TripsService
     */
    private $tripService;

    /**
     * TripsController constructor.
     * @param TripsService $tripService
     */
    public function __construct(TripsService $tripService)
    {
        $this->tripService = $tripService;
    }

    /**
     * @param TripsRequest $request
     * @return mixed
     */
    public function index(TripsRequest $request)
    {
        $start_id = $this->tripService->getStationId($request->input('start'));
        $end_id = $this->tripService->getStationId($request->input('end'));
        $seats = $this->tripService->getAvailableSeats($start_id, $end_id);

        return response()->json(['data' => TripResource::collection($seats)]);
    }

    public function book(TripBookRequest $request)
    {
        $user_id = auth()->user()->id;
        try {
            $seat =$this->tripService->bookSeat($request->bus_id, $user_id, $request->pickup_id, $request->destination_id);
        } catch (BookingE $exception) {
            return response()->json("Something Went Wrong", 403);
        }

        return response()->json(["message"=>"Your seat booked successfully","data"=> new SeatResource($seat) ]);
    }
}
