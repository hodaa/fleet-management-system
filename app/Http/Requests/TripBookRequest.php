<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\TripsService;

class TripBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'seat_id' =>'required|exists:buses,seat_no',
            'pickup_point' =>'required|exists:stations,name',
            'destination_point' => 'required|exists:stations,name'
        ];
    }

    public function prepareForValidation(): TripBookRequest
    {
        $pickup_id =app(TripsService::class)->getStationId(request()->pickup_point);
        $destination_id = app(TripsService::class)->getStationId(request()->destination_point);

        $line_id= app(TripsService::class)->getLineId($pickup_id, $destination_id);


        $data = [
            'pickup_id' => app(TripsService::class)->getStationId(request()->pickup_point),
            'destination_id' => app(TripsService::class)->getStationId(request()->destination_point),
            'bus_id' => app(TripsService::class)->getBusId($line_id, request()->seat_id)
        ];
        return $this->merge($data);
    }
}
