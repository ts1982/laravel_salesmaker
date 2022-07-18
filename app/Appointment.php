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

    public static function makeCalendar($period)
    {
        Carbon::setLocale('ja');
        $time_zone = self::$time_zone;

        if ($period) {
            $start_day = Carbon::parse($period . '-01')->startOfMonth();
            $middle_day = Carbon::parse($period . '-01')->startOfMonth()->addDays(15);
            $end_day = Carbon::parse($period . '-01')->endOfMonth();
        } else {
            $start_day = Carbon::now()->startOfMonth();
            $middle_day = Carbon::now()->startOfMonth()->addDays(15);
            $end_day = Carbon::now()->endOfMonth();
        }

        return [$time_zone, $start_day, $middle_day, $end_day];
    }

    public static function getMonthlyAppointersAppointments($user, $period)
    {
        if (!$period) {
            $period = Carbon::now();
        }
        $appointments = Appointment::where('day', 'like', "{$period}%")->where('user_id', $user->id)->get();

        return $appointments;
    }

    public static function getMonthlySellersAppointments($user, $period)
    {
        if (!$period) {
            $period = Carbon::now();
        }
        $appointments = Appointment::where('day', 'like', "{$period}%")->where('seller_id', $user->id)->get();

        return $appointments;
    }

    public function thisAppointerHas()
    {
        $user_id = $this->user_id;
        $appointer = User::find($user_id);

        return $appointer;
    }

    public function thisSellerHas()
    {
        $seller_id = $this->seller_id;
        $seller = User::find($seller_id);

        return $seller;
    }

    public static function isFuture($day, $hour)
    {
        $get_param = Carbon::parse("{$day} {$hour}:00:00");
        $now = Carbon::now();
        if ($get_param->greaterThan($now)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getNextPeriod($period) // ex) 2022-07
    {
        if ($period) {
            $day = Carbon::parse($period . '-1');
        } else {
            $day = Carbon::now();
        }

        $day->addMonthsNoOverflow();
        $period = $day->format('Y-m');

        return $period; // ex) 2022-08
    }

    public static function getPrevPeriod($period) // ex) 2022-07
    {
        if ($period) {
            $day = Carbon::parse($period . '-1');
        } else {
            $day = Carbon::now();
        }

        $day->subMonthsNoOverflow();
        $period = $day->format('Y-m');

        return $period; // ex) 2022-06
    }
}
