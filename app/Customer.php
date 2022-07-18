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

    public function isLatestAppointment($day, $hour)
    {
        $latest_appointment = $this->appointments->sortByDesc('day')->first();
        $latest_date = Carbon::parse("{$latest_appointment->day} {$latest_appointment->hour}:00:00");
        $target_date = Carbon::parse("{$day} {$hour}:00:00");

        if ($target_date->gt($latest_date)) {
            return true;
        } else {
            return false;
        }
    }
}
