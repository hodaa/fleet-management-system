<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripsRequest;
use App\Http\Resources\TripsResource;
use App\Services\TripsService;

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
        return response()->json(['data' => TripsResource::collection($seats)]);
    }
}
