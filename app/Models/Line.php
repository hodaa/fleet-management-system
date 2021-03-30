<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

}
