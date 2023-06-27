<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
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

    public function seller_calendar(Request $request)
    {
        if ($request->customer) {
            $customer = Customer::find($request->customer);

            // 未訪問のアポイントチェック
            if (!$request->period && $customer->existsNotVisitedAppointment()) {
                return back()->with('warning', '未訪問のアポイントがあります。');
            }
        } else {
            $customer = '';
        }

        if ($request->seller) { // 日時変更から
            $user = User::find($request->seller);
            $seller_appointment = $request->seller_appointment;
            $seller = $user;
            $day = $request->day;
            $hour = $request->hour;
        } else if (User::roleIs('appointer')) { // 追加作成から
            $latest_appointment = Appointment::where('customer_id', $customer->id)->latest()->first();
            $user = $latest_appointment->thisSellerHas();
            $seller_appointment = '';
            $seller = $user;
            $day = '';
            $hour = '';
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

        $appointments = Appointment::getMonthlySellersAppointments($user, $period);
        foreach ($appointments as $appointment) {
            $hasAppointments[$appointment->day][$appointment->hour] = true;
        }

        return view('users.seller_calendar', compact('period', 'time_zone', 'start_day', 'middle_day', 'end_day', 'hasAppointments', 'user', 'seller_appointment', 'seller', 'customer', 'day', 'hour'));
    }

    public function appointer_calendar(Request $request)
    {
        $user = Auth::user();

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

        return view('users.appointer_calendar', compact('period', 'time_zone', 'start_day', 'middle_day', 'end_day', 'hasAppointments', 'user'));
    }

    public function seller_record(Request $request)
    {
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now();
            $period = $period->format('Y-m');
        }

        //
        $user = Auth::user();
        $appointments = Appointment::where('seller_id', $user->id)->where('day', 'like', "$period%")->orderBy('day', 'asc')->orderBy('hour', 'asc')->get();

        $total = $appointments->whereIn('status', [2, 3])->count();
        $contract_count = $appointments->where('status', 3)->count();
        if ($total !== 0) {
            $rate = number_format($contract_count / $total * 100, 1);
        } else {
            $rate = 0;
        }
        $rank = $user->getRank($rate, 'seller');

        // ソート
        $sort_query = Appointment::STATUS_LIST;

        if ($request->sort) {
            $key = array_search($request->sort, $sort_query);
            $appointments = Appointment::where('seller_id', $user->id)->where('status', $key)->where('day', 'like', "$period%")->orderBy('day', 'asc')->orderBy('hour', 'asc')->get();
        }

        return view('users.seller_record', compact('appointments', 'period', 'total', 'contract_count', 'rate', 'rank', 'sort_query'));
    }

    public function appointer_record(Request $request)
    {
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now();
            $period = $period->format('Y-m');
        }

        //
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)->where('day', 'like', "$period%")->orderBy('day', 'asc')->orderBy('hour', 'asc')->get();

        $total = $appointments->whereIn('status', [2, 3])->count();
        $contract_count = $appointments->where('status', 3)->count();
        if ($total !== 0) {
            $rate = number_format($contract_count / $total * 100, 1);
        } else {
            $rate = 0;
        }
        $rank = $user->getRank($rate, 'appointer');


        // ソート
        $sort_query = Appointment::STATUS_LIST;

        if ($request->sort) {
            $key = array_search($request->sort, $sort_query);
            $appointments = Appointment::where('user_id', $user->id)->where('status', $key)->where('day', 'like', "$period%")->orderBy('day', 'asc')->orderBy('hour', 'asc')->get();
        }

        return view('users.appointer_record', compact('appointments', 'period', 'total', 'contract_count', 'rate', 'rank', 'sort_query'));
    }
}
