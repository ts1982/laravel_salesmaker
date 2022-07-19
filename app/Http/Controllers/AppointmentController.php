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
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = '';
        }

        // カレンダー作成
        list($time_zone, $start_day, $middle_day, $end_day) = Appointment::makeCalendar($period);

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
        for ($day; $day <= $end_day; $day->addDay()) {
            foreach ($time_zone as $time) {
                $count = Appointment::where('day', $day)->where('hour', $time)->count();
                $appointments_later[$day->format('Y-m-d')][$time] = $total_seller - $count;
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

        return view('appointments.index', compact('period', 'time_zone', 'start_day', 'middle_day', 'end_day', 'appointments_prev', 'appointments_later', 'appointment', 'customer'));
    }

    public function create(Request $request)
    {
        $day = $request->day;
        $hour = $request->hour;

        // 登録日のチェック
        if (!Appointment::isFuture($day, $hour)) {
            return back()->with('warning', '過去の指定はできません。');
        }

        // 新規か既存か
        if ($request->has('customer')) {
            $customer = Customer::find($request->customer);

            // 未訪問のアポイントチェック
            if (!$customer->isLatestAppointment($day, $hour)) {
                return back()->with('warning', '指定した日付より後に、未訪問のアポイントがあります。');
            }
        } else {
            $customer = '';
        }

        return view('appointments.create', compact('day', 'hour', 'customer'));
    }

    public function store(Request $request)
    {

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $appointment = new Appointment();

            if (!empty($request->customer)) {

                $customer = $request->customer;
                $appointment->customer_id = $customer;

                if ($user->role === 'seller') {
                    $appointment->seller_id = $user->id;
                } else {
                    $target_appointment = Appointment::where('customer_id', $customer)->orderBy('day', 'desc')->get()->first();
                    $latest_seller = User::find($target_appointment->seller_id);
                    $appointment->seller_id = $latest_seller->id;
                }
            } else {
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

                $customer = new Customer();
                $customer->name = $request->name;
                $customer->address = $request->address;
                $customer->tel = $request->tel;
                $customer->save();

                $appointment->customer_id = $customer->id;

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
            }

            $appointment->day = $request->day;
            $appointment->hour = $request->hour;
            $appointment->content = $request->content;
            $appointment->user_id = $user->id;
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

    public function edit(Appointment $appointment, Request $request)
    {
        if ($request->has('day', 'hour')) {
            $day = $request->day;
            $hour = $request->hour;
            // 登録日のチェック
            if (!Appointment::isFuture($day, $hour)) {
                return back()->with('warning', '過去の指定はできません。');
            }
        } else {
            $day = '';
            $hour = '';
        }


        $appointer = $appointment->user;
        $seller = User::find($appointment->seller_id);
        $seller_appointment = $appointment;

        return view('appointments.edit', compact('appointment', 'seller_appointment', 'appointer', 'seller', 'day', 'hour'));
    }

    public function update(Appointment $appointment, Request $request)
    {
        if ($request->day && $request->hour) {
            $appointment->day = $request->day;
            $appointment->hour = $request->hour;
        }
        $appointment->content = $request->content;
        $appointment->update();

        return redirect()->route('appointments.show', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        $customer = $appointment->customer;

        $appointment->delete();

        return redirect()->route('customers.show', compact('customer'));
    }

    public function byday(Request $request)
    {
        $user = Auth::user();
        $day = new Carbon($request->day);
        if ($user->role === 'seller') {
            $appointments = Appointment::where('day', $day->format('Y-m-d'))->where('seller_id', $user->id)->orderBy('hour', 'asc')->get();
        } else if ($user->role === 'appointer') {
            $appointments = Appointment::where('day', $day->format('Y-m-d'))->where('user_id', $user->id)->get();
        }

        return view('appointments.byday', compact('appointments', 'day'));
    }

    public function change_status(Request $request)
    {
        $appointment = Appointment::find($request->appointment);
        $appointment->status = $request->status;
        $appointment->update();

        $day = $request->day;

        return redirect()->route('appointments.byday', compact('day'));
    }
}
