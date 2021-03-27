<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    /**
     * @return mixed
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }


}
