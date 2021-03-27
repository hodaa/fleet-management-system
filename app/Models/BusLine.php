<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusLine extends Model
{
    protected $fillable =['pickup_id','destination_id','user_id'];
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
     * @return mixed
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
