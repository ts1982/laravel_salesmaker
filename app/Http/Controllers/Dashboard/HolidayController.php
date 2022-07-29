<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Customer;
use App\Appointment;
use App\Holiday;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function holiday(Request $request)
    {
        $today = Carbon::now();

        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            if ($today->day <= 15) {
                $period = Carbon::now()->format('Y-m/first');
            } else {
                $period = Carbon::now()->format('Y-m/second');
            }
        }

        // カレンダー作成
        list($start_day, $end_day, $sellers_holidays, $half) = Holiday::getSellersHolidays($period);

        $sellers = User::where('role', 'seller')->get();
        $calendar = [];
        $start = $start_day->copy();
        foreach ($sellers as $seller) {
            $start = $start_day->copy();
            for ($start; $start < $end_day; $start->addDay()) {
                if (Appointment::where('seller_id', $seller->id)->where('day', $start)->count()) {
                    $calendar[$seller->id][$start->format('Y-m-d')] = 2;
                } else if (isset($sellers_holidays[$start->format('Y-m-d')][$seller->id])) {
                    $calendar[$seller->id][$start->format('Y-m-d')] = 1;
                } else {
                    $calendar[$seller->id][$start->format('Y-m-d')] = 0;
                }
            }
        }

        $info = $request->info;

        return view('dashboard.appointments.holiday', compact('period', 'start_day', 'end_day', 'calendar', 'half', 'info'));
    }

    public function holiday_store(Request $request)
    {
        $start = date('Y-m-d', strtotime($request->start_day));
        $end = date('Y-m-d', strtotime($request->end_day));
        Holiday::whereBetween('day', [$start, $end])->delete();
        if ($request->holidays) {
            foreach ($request->holidays as $array) {
                $array = explode(',', $array);
                $holiday = new Holiday();
                $holiday->user_id = $array[0];
                $holiday->day = $array[1];
                $holiday->save();
            }
        }

        $period = $request->period;

        $info = '設定を変更しました。';

        return redirect()->route('dashboard.appointments.holiday', compact('period', 'info'));
    }
}
