<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    const TIME_ZONE = [10, 13, 16, 19];
    const STATUS_LIST = ['未訪問', '再訪問', 'NG', '契約'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    public static function makeCalendar($period)
    {
        Carbon::setLocale('ja');
        $time_zone = self::TIME_ZONE;
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

        $sellers_count = [];
        $day = $start_day->copy();
        for ($day; $day <= $end_day; $day->addDay()) {
            $sellers_count[$day->format('Y-m-d')] = User::countSellersInOperate($day->format('Y-m-d'));
        }

        $holidays = Holiday::whereBetween('day', [$start_day, $end_day])->get();
        $sellers_id = User::where('role', 'seller')->pluck('id');
        $sellers_holidays = [];

        foreach ($holidays as $holiday) {
            foreach ($sellers_id as $id) {
                if ($holiday->user_id === $id) {
                    $sellers_holidays[$holiday->day][$id] = 1;
                }
            }
        }

        return [$time_zone, $start_day, $middle_day, $end_day, $sellers_holidays, $sellers_count];
    }

    public function getDayName()
    {
        $day = Carbon::parse($this->day);
        $day_name = mb_substr($day->dayName, 0, 1);

        return $day_name;
    }

    public static function isNow($day, $time)
    {
        $target = Carbon::parse("{$day->format('Y-m-d')} {$time}:00:00");
        $now = Carbon::now();
        $diff = 3; // アポイントの間隔設定値

        if ($target->isToday()) {
            if ($target->copy()->lt($now) && $target->copy()->addHour($diff)->gt($now)) {
                return 'mark-now';
            }
        }
    }

    public static function getMonthlyAppointersAppointments($user, $period)
    {
        $today = Carbon::now();

        $carbon = new Carbon($period . '-01');
        $end_of_period = $carbon->addMonth()->addDays(15);

        $appointments = $user->appointments->whereBetween('day', [$period . '-01', $end_of_period]);

        return $appointments;
    }

    public static function getMonthlySellersAppointments($user, $period)
    {
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

    public function statusIs()
    {
        $key = $this->status;
        if ($key === 0) {
            $badge = 'badge badge-pill badge-secondary';
        }
        if ($key === 1) {
            $badge = 'badge badge-pill badge-warning';
        }
        if ($key === 2) {
            $badge = 'badge badge-pill badge-danger';
        }
        if ($key === 3) {
            $badge = 'badge badge-pill badge-success';
        }
        $status = self::STATUS_LIST[$key];

        return [$badge, $status];
    }
}
