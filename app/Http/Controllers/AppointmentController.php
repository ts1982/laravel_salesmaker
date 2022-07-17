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
        list($time_zone, $start_day, $middle_day, $end_day) = Appointment::makeCalendar($request->day);

        $total_seller = User::where('role', 'seller')->count();

        $appointments_prev = [];
        $day = $start_day->copy();
        for ($day; $day < $middle_day; $day->addDay()) {
            foreach ($time_zone as $time) {
                $count = Appointment::where('day', $day)->where('hour', $time)->count();
                $appointments_prev[$day->format('Y-m-d')][$time] = $total_seller - $count;
            }
        }

        $appointments_later = [];
        $day = $middle_day->copy();
        for ($day; $day < $end_day; $day->addDay()) {
            foreach ($time_zone as $time) {
                $count = Appointment::where('day', $day)->where('hour', $time)->count();
                $appointments_later[$day->format('Y-m-d')][$time] = $total_seller - $count;
            }
        }

        return view('appointments.index', compact('time_zone', 'start_day', 'middle_day', 'end_day', 'appointments_prev', 'appointments_later'));
    }

    public function create(Request $request)
    {
        $day = $request->day;
        $hour = $request->hour;

        return view('appointments.create', compact('day', 'hour'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'address' => 'required',
                'tel' => 'required',
                'content' => 'required',
            ],
            [
                'name.required' => '氏名を入力してください。',
                'address.required' => '住所を入力してください。',
                'tel.required' => '電話番号を入力してください。',
                'content.required' => 'ヒアリング内容を入力してください。',
            ]
        );

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
                $all_seller_id = User::where('role', 'seller')->pluck('id')->toArray();
                $disabled_seller_id = Appointment::where('day', $request->day)->where('hour', $request->hour)->pluck('seller_id')->toArray();
                $selected_id = array_diff($all_seller_id, $disabled_seller_id);
                $selected_id = $selected_id[array_rand($selected_id, 1)];
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

    public function byday(Request $request)
    {
        $user = Auth::user();
        $day = new Carbon($request->day);
        if ($user->role === 'seller') {
            $appointments = Appointment::where('day', $day->format('Y-m-d'))->where('seller_id', $user->id)->get();
        } else if ($user->role === 'appointer') {
            $appointments = Appointment::where('day', $day->format('Y-m-d'))->where('user_id', $user->id)->get();
        }

        return view('appointments.byday', compact('appointments', 'day'));
    }
}
