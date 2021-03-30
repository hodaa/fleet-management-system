<?php

namespace App\Rules;

use App\Services\TripService;
use Illuminate\Contracts\Validation\Rule;

class CheckRouteExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $start_id= app(TripService::class)->getStationId(request()->start);
        $end_id = app(TripService::class)->getStationId(request()->end);



        return  app(TripService::class)->getLineId($start_id, $end_id) ? true :false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Route Does not exist.';
    }
}
