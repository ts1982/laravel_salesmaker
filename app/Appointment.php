<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    public static $time_zone = [10, 13, 16, 19];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
