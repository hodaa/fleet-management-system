<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusLine extends Model
{
    /**
     * @return mixed
     */
    public function destination(){
        return $this->belongsTo(Station::class);
    }

    /**
     * @return mixed
     */
    public function pickup(){
        return $this->belongsTo(Station::class);
    }

    /**
     * @return mixed
     */
    public function line(){
        return $this->belongsTo(Line::class);
    }
}
