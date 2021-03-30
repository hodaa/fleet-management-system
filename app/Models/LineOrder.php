<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineOrder extends Model
{
    public function line()
    {
        return $this->belongsTo(Line::class);
    }
}
