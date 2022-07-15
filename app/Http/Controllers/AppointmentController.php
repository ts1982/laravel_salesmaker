<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('ja');

        if ($request->day) {
            $start_day = Carbon::parse($request->day)->startOfMonth();
            $middle_day = Carbon::parse($request->day)->startOfMonth()->addDays(15);
            $end_day = Carbon::parse($request->day)->endOfMonth();
        } else {
            $start_day = Carbon::now()->startOfMonth();
            $middle_day = Carbon::now()->startOfMonth()->addDays(15);
            $end_day = Carbon::now()->endOfMonth();
        }

        if ($request->day && $request->hour) {
            $day = $request->day;
            $hour = $request->hour;
        } else {
            $day = '';
            $hour = '';
        }

        return view('appointments.index', compact('start_day', 'middle_day', 'end_day', 'day', 'hour'));
    }
}
