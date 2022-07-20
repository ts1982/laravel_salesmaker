<?php

namespace App\Http\Controllers;

use App\User;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function edit_password()
    {
        return view('users.edit_password');
    }

    public function update_password(Request $request)
    {
        if ($request->password === $request->confirm_password) {
            $user = Auth::user();

            $user->password = bcrypt($request->password);
            $user->update();
        } else {
            return back()->with('warning', 'パスワードが一致しません。');
        }

        return redirect('/');
    }

    public function calendar(Request $request)
    {
        if ($request->seller) {
            $user = User::find($request->seller);
            $seller_appointment = $request->seller_appointment;
            $seller = $request->seller;
            $day = $request->day;
            $hour = $request->hour;
        } else {
            $user = Auth::user();
            $seller_appointment = '';
            $seller = '';
            $day = '';
            $hour = '';
        }

        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now()->format('Y-m');
        }

        // カレンダー作成
        list($time_zone, $start_day, $middle_day, $end_day) = Appointment::makeCalendar($period);
        $hasAppointments = [];

        if ($user->role === 'seller') {
            $appointments = Appointment::getMonthlySellersAppointments($user, $period);
            foreach ($appointments as $appointment) {
                $hasAppointments[$appointment->day][$appointment->hour] = true;
            }
        }

        if ($user->role === 'appointer') {
            $appointments = Appointment::getMonthlyAppointersAppointments($user, $period);
            foreach ($appointments as $appointment) {
                if (!isset($hasAppointments[$appointment->day][$appointment->hour])) {
                    $hasAppointments[$appointment->day][$appointment->hour] = 0;
                }
                $hasAppointments[$appointment->day][$appointment->hour] += 1;
            }
        }

        if ($request->customer) {
            $customer = $request->customer;
        } else {
            $customer = '';
        }

        return view('users.calendar', compact('period', 'time_zone', 'start_day', 'middle_day', 'end_day', 'hasAppointments', 'user', 'seller_appointment', 'seller', 'customer', 'day', 'hour'));
    }
}
