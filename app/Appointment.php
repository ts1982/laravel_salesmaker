<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    public static $time_zone = [10, 13, 16, 19];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public static function makeCalendar($day)
    {
        Carbon::setLocale('ja');
        $time_zone = Appointment::$time_zone;

        if ($day) {
            $start_day = Carbon::parse($day)->startOfMonth();
            $middle_day = Carbon::parse($day)->startOfMonth()->addDays(15);
            $end_day = Carbon::parse($day)->endOfMonth();
        } else {
            $start_day = Carbon::now()->startOfMonth();
            $middle_day = Carbon::now()->startOfMonth()->addDays(15);
            $end_day = Carbon::now()->endOfMonth();
        }

        return [$time_zone, $start_day, $middle_day, $end_day];
    }

    public static function getMonthlyAppointersAppointments($user, $day)
    {
        if (!$day) {
            $day = Carbon::now();
        }
        $day = date('Y-m', strtotime($day));
        $appointments = Appointment::where('day', 'like', "{$day}%")->where('user_id', $user->id)->get();

        return $appointments;
    }

    public static function getMonthlySellersAppointments($user, $day)
    {
        if (!$day) {
            $day = Carbon::now();
        }
        $day = date('Y-m', strtotime($day));
        $appointments = Appointment::where('day', 'like', "{$day}%")->where('seller_id', $user->id)->get();

        return $appointments;
    }

    public static function getAppointer($appointment)
    {
        $user_id = $appointment->user_id;
        $appointer = User::find($user_id);

        return $appointer;
    }

    public static function getSeller($appointment)
    {
        $seller_id = $appointment->seller_id;
        $seller = User::find($seller_id);

        return $seller;
    }
}
