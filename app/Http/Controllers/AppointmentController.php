<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('ja');
        $time_zone = Appointment::$time_zone;

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

        return view('appointments.index', compact('time_zone', 'start_day', 'middle_day', 'end_day', 'day', 'hour'));
    }

    public function create(Request $request)
    {
        $day = $request->day;
        $hour = $request->hour;

        return view('appointments.create', compact('day', 'hour'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            $customer = new Customer();
            $customer->name = $request->name;
            $customer->address = $request->address;
            $customer->tel = $request->tel;
            $customer->save();

            $appointment = new Appointment();

            if ($user->role === 'appointer') {
                $sellers_id = User::where('role', 'seller')->pluck('id')->toArray();
                $selected_id = $sellers_id[array_rand($sellers_id, 1)];
                $appointment->seller_id = $selected_id;
            } else if ($user->role === 'seller') {
                $appointment->seller_id = $user->id;
            } else {
                // nothing to do
            }

            $appointment->day = $request->day;
            $appointment->hour = $request->hour;
            $appointment->content = $request->content;
            $appointment->user_id = $user->id;
            $appointment->customer_id = $customer->id;
            $appointment->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


        return redirect()->route('appointments.show', compact('appointment'));
    }

    public function show(Appointment $appointment)
    {
        $appointer = $appointment->user;
        $seller = User::find($appointment->seller_id);

        return view('appointments.show', compact('appointment', 'appointer', 'seller'));
    }
}
