<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Customer;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = '';
        }

        // カレンダー作成
        list($time_zone, $start_day, $middle_day, $end_day, $sellers_holidays, $sellers_count) = Appointment::makeCalendar($period);

        $total_seller = User::where('role', 'seller')->count();

        $appointments_prev = [];
        $day = $start_day->copy();
        for ($day; $day < $middle_day; $day->addDay()) {
            if (!empty($sellers_holidays[$day->format('Y-m-d')])) {
                $holiday_count = count($sellers_holidays[$day->format('Y-m-d')]);
            } else {
                $holiday_count = 0;
            }
            $total_count = $sellers_count[$day->format('Y-m-d')];
            foreach ($time_zone as $time) {
                $count = Appointment::where('day', $day)->where('hour', $time)->count();
                $appointments_prev[$day->format('Y-m-d')][$time] = $total_count - $count - $holiday_count;
            }
        }

        $appointments_later = [];
        $day = $middle_day->copy();
        for ($day; $day <= $end_day; $day->addDay()) {
            if (!empty($sellers_holidays[$day->format('Y-m-d')])) {
                $holiday_count = count($sellers_holidays[$day->format('Y-m-d')]);
            } else {
                $holiday_count = 0;
            }
            $total_count = $sellers_count[$day->format('Y-m-d')];
            foreach ($time_zone as $time) {
                $count = Appointment::where('day', $day)->where('hour', $time)->count();
                $appointments_later[$day->format('Y-m-d')][$time] = $total_count - $count - $holiday_count;
            }
        }

        //
        if ($request->has('appointment')) {
            $appointment = $request->appointment;
        } else {
            $appointment = '';
        }

        if ($request->has('customer')) {
            $customer = $request->customer;
        } else {
            $customer = '';
        }

        return view('dashboard.appointments.index', compact('period', 'time_zone', 'start_day', 'middle_day', 'end_day', 'appointments_prev', 'appointments_later', 'appointment', 'customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        $seller_appointment = $appointment;
        $contents = $appointment->contents->sortByDesc('created_at');

        $status_list = Appointment::STATUS_LIST;

        return view('dashboard.appointments.show', compact('appointment', 'seller_appointment', 'contents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        $sellers = User::where('role', 'seller')->get();
        $appointers = User::where('role', 'appointer')->get();
        $status_list = Appointment::STATUS_LIST;

        return view('dashboard.appointments.edit', compact('appointment', 'sellers', 'appointers', 'status_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Appointment $appointment, Request $request)
    {
        $appointment->user_id = $request->appointer;
        $appointment->seller_id = $request->seller;
        $appointment->status = $request->status;
        $appointment->save();

        return redirect()->route('dashboard.appointments.show', compact('appointment'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $customer = $appointment->customer;

        $appointment->delete();

        return redirect()->route('dashboard.customers.show', compact('customer'));
    }

    public function byday(Request $request)
    {
        // $user = Auth::user();
        $day = new Carbon($request->day);
        // if ($user->role === 'seller') {
        $appointments = Appointment::where('day', $day->format('Y-m-d'))->orderBy('hour', 'asc')->get();
        // } else if ($user->role === 'appointer') {
        //     $appointments = Appointment::where('day', $day->format('Y-m-d'))->where('user_id', $user->id)->get();
        // }

        $status_list = Appointment::STATUS_LIST;

        return view('dashboard.appointments.byday', compact('appointments', 'day', 'status_list'));
    }
}
