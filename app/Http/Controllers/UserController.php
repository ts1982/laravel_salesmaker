<?php

namespace App\Http\Controllers;

use App\User;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function calendar(Request $request)
    {
        list($time_zone, $start_day, $middle_day, $end_day) = Appointment::makeCalendar($request->day);

        $user = Auth::user();
        $hasAppointments = [];

        if (User::roleIs('seller')) {
            $appointments = Appointment::getMonthlySellersAppointments($user, $request->day);
            foreach ($appointments as $appointment) {
                $hasAppointments[$appointment->day][$appointment->hour] = true;
            }
        }

        if (User::roleIs('appointer')) {
            $appointments = Appointment::getMonthlyAppointersAppointments($user, $request->day);
            foreach ($appointments as $appointment) {
                if (!isset($hasAppointments[$appointment->day][$appointment->hour])) {
                    $hasAppointments[$appointment->day][$appointment->hour] = 0;
                }
                $hasAppointments[$appointment->day][$appointment->hour] += 1;
            }
        }

        return view('users.calendar', compact('time_zone', 'start_day', 'middle_day', 'end_day', 'hasAppointments'));
    }
}
