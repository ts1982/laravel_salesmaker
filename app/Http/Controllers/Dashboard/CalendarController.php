<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Customer;
use App\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function seller_calendar(Request $request)
    {
        if ($request->seller_id) {
            $user = User::find($request->seller_id);
            $seller_id = $request->seller_id;
        } else {
            $user = User::where('role', 'seller')->orderBy('id')->first();
            $seller_id = '';
        }
        $sellers = User::where('role', 'seller')->orderBy('id')->get();

        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now()->format('Y-m');
        }

        // カレンダー作成
        list($time_zone, $start_day, $middle_day, $end_day) = Appointment::makeCalendar($period);
        $hasAppointments = [];

        $appointments = Appointment::getMonthlySellersAppointments($user, $period);
        foreach ($appointments as $appointment) {
            $hasAppointments[$appointment->day][$appointment->hour] = true;
        }

        return view('dashboard.calendar.seller_calendar', compact('period', 'time_zone', 'start_day', 'middle_day', 'end_day', 'hasAppointments', 'user', 'sellers', 'seller_id'));
    }

    public function appointer_calendar(Request $request)
    {
        if ($request->appointer_id) {
            $user = User::find($request->appointer_id);
            $appointer_id = $request->appointer_id;
        } else {
            $user = User::where('role', 'appointer')->orderBy('id')->first();
            $appointer_id = '';
        }
        $appointers = User::where('role', 'appointer')->orderBy('id')->get();

        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now()->format('Y-m');
        }

        // カレンダー作成
        list($time_zone, $start_day, $middle_day, $end_day) = Appointment::makeCalendar($period);
        $hasAppointments = [];

        $appointments = Appointment::getMonthlyAppointersAppointments($user, $period);
        foreach ($appointments as $appointment) {
            if (!isset($hasAppointments[$appointment->day][$appointment->hour])) {
                $hasAppointments[$appointment->day][$appointment->hour] = 0;
            }
            $hasAppointments[$appointment->day][$appointment->hour] += 1;
        }

        return view('dashboard.calendar.appointer_calendar', compact('period', 'time_zone', 'start_day', 'middle_day', 'end_day', 'hasAppointments', 'user', 'appointers', 'appointer_id'));
    }
}
