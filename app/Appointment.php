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
        $target_first_day = Carbon::parse($period . '-01');
        $today = Carbon::now();

        if ($period) {
            $start_day = $target_first_day->copy()->startOfMonth();
            $middle_day = $target_first_day->copy()->startOfMonth()->addDays(15);
            $end_day = $target_first_day->copy()->endOfMonth();
        } else {
            $start_day = $today->copy()->startOfMonth();
            $middle_day = $today->copy()->startOfMonth()->addDays(15);
            $end_day = $today->copy()->endOfMonth();
        }

        if ($today->day > 15) {
            $start_day = $start_day->copy()->addDays(15);
            $middle_day = $start_day->copy()->addMonth()->startOfMonth();
            $end_day = $middle_day->copy()->addDays(14);
        }

        return [$time_zone, $start_day, $middle_day, $end_day];
    }

    public function getDayName()
    {
        $day = Carbon::parse($this->day);
        $day_name = mb_substr($day->dayName, 0, 1);

        return $day_name;
    }

    public static function getMonthlyAppointersAppointments($user, $period)
    {
        if (!$period) {
            $period = Carbon::now()->format('Y-m');
        }

        $carbon = new Carbon($period . '-1');
        $end_of_period = $carbon->addMonth()->addDays(15);

        $appointments = $user->appointments->whereBetween('day', [$period . '-1', $end_of_period]);

        return $appointments;
    }

    public static function getMonthlySellersAppointments($user, $period)
    {
        if (!$period) {
            $period = Carbon::now()->format('Y-m');
        }

        $carbon = new Carbon($period . '-1');
        $end_of_period = $carbon->addMonth()->addDays(15);

        $appointments = Appointment::whereBetween('day', [$period . '-1', $end_of_period])->where('seller_id', $user->id)->get();

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

    public function isDone()
    {
        $now = Carbon::now();
        $target_appointment = Carbon::parse("{$this->day} {$this->hour}:00:00");
        if ($target_appointment->lt($now)) {
            return '訪問済';
        } else {
            return '未訪問';
        }
    }
}
