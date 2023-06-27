<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    public static function getSellersHolidays($period) // ex) 2022-08/former
    {
        Carbon::setLocale('ja');
        $today = Carbon::now();

        // 月の前半と後半で場合分け
        if (strpos($period, 'former')) {
            $target_first_day = Carbon::parse(explode('/', $period)[0] . '-01');
            $end_day = $target_first_day->copy()->startOfMonth()->addDays(15);
            $half = 'later';
        } else if (strpos($period, 'later')) {
            $target_first_day = Carbon::parse(explode('/', $period)[0] . '-16');
            $end_day = $target_first_day->copy()->endOfMonth();
            $half = 'former';
        }

        $start_day = $target_first_day->copy();
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

        return [$start_day, $end_day, $sellers_holidays, $half];
    }
}
