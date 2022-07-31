<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }

    public function existsNotVisitedAppointment()
    {
        $status = $this->appointments->pluck('status');

        if ($status->contains(0)) {
            return true;
        } else {
            return false;
        }
    }
}
