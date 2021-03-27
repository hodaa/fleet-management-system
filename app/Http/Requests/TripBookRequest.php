<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'id' =>'required|exists:bus_lines,id',
            'pickup_point' =>'required|exists:stations,id',
            'destination_point' => 'required|exists:stations,id'

        ];
    }

    public function prepareForValidation()
    {
        $data = [
            'id' => request()->id,
        ];
        return $this->merge($data);
    }
}
