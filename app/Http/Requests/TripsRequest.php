<?php

namespace App\Http\Requests;

use App\Models\Station;
use App\Rules\CheckRouteExists;
use App\Services\TripsService;
use Illuminate\Foundation\Http\FormRequest;

class TripsRequest extends FormRequest
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
            'start' =>'required|exists:stations,name',
            'end'=> ['bail','required','exists:stations,name',new CheckRouteExists()]
        ];
    }


//    public function prepareForValidation()
//    {
//        $data = [
//            'start_id' => app(TripsService::class)->getStationId(request()->start),
//            'end_id' => app(TripsService::class)->getStationId(request()->end)
//        ];
//        return $this->merge($data);
//    }
}
