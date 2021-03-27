<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedSeat extends Model
{
    protected $fillable =['bus_id','pickup_id','destination_id','user_id'];


    /**
     * @return mixed
     */
    public function destination()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * @return mixed
     */
    public function pickup()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bus()
    {
        return $this->belongsTo(bus::class);
    }
}
