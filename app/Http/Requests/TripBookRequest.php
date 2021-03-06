<?php

namespace App\Http\Requests;

use App\Rules\CheckSeatIsBooked;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\TripService;

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
            'destination_point' => ['required','exists:stations,name', new CheckSeatIsBooked()]
        ];
    }

    public function prepareForValidation(): TripBookRequest
    {
        $pickup_id =app(TripService::class)->getStationId(request()->pickup_point);
        $destination_id = app(TripService::class)->getStationId(request()->destination_point);
        $line_id= app(TripService::class)->getLineId($pickup_id, $destination_id);

        $data = [
            'pickup_id' => $pickup_id,
            'destination_id' => $destination_id,
            'bus_id' => app(TripService::class)->getBusId($line_id, request()->seat_id)
        ];
        return $this->merge($data);
    }
}
