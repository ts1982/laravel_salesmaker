<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Appointment;
use App\Content;
use App\Holiday;
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
        } else {
            $customer = '';
        }

        return view('appointments.create', compact('day', 'hour', 'customer'));
    }

    public function store(Request $request)
    {
        if (empty($request->customer)) {
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
        } else {
            $request->validate(
                [
                    'content' => 'required',
                ],
                [
                    'content.required' => 'ヒアリング内容を入力してください。',
                ]
            );
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $appointment = new Appointment();

            if (!empty($request->customer)) { // 既存

                $customer = $request->customer;
                $appointment->customer_id = $customer;
                $appointment->user_id = Appointment::where('customer_id', $customer)->latest()->first()->user_id;

                if ($user->role === 'seller') {
                    $appointment->seller_id = $user->id;
                } else {
                    $customer = Customer::find($customer);
                    if ($customer->user_id == null) {
                        return redirect()->back()->with('warning', '営業担当者が設定されていません。管理者に問い合わせてください。');
                    } else {
                        $appointment->seller_id = $customer->user_id;
                    }
                }
            } else { // 新規
                $customer = new Customer();
                $customer->name = $request->name;
                $customer->address = $request->address;
                $customer->tel = $request->tel;
                $customer->save();

                $appointment->customer_id = $customer->id;
                $appointment->user_id = $user->id;

                if ($user->role === 'appointer') {
                    $all_seller = User::sellersInOperate($request->day);
                    $all_seller_id = $all_seller->pluck('id')->toArray();
                    $hasAppointment_seller_id = Appointment::where('day', $request->day)->where('hour', $request->hour)->pluck('seller_id')->toArray();
                    $holiday_seller_id = Holiday::where('day', $request->day)->pluck('user_id')->toArray();
                    $disabled_seller_id = array_merge($hasAppointment_seller_id, $holiday_seller_id);
                    $selected_id = array_diff($all_seller_id, $disabled_seller_id);
                    $selected_id = $selected_id[array_rand($selected_id, 1)];
                    $appointment->seller_id = $selected_id;
                    $customer->user_id = $selected_id;
                } else if ($user->role === 'seller') {
                    $appointment->seller_id = $user->id;
                    $customer->user_id = $user->id;
                } else {
                    // nothing to do
                }
                $customer->update();
            }

            $appointment->day = $request->day;
            $appointment->hour = $request->hour;
            $appointment->save();

            $content = new Content();
            $content->appointment_id = $appointment->id;
            $content->user_id = $user->id;
            $content->content = $request->content;
            $content->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('appointments.show', compact('appointment'));
    }

    public function show(Appointment $appointment, Request $request)
    {
        $seller_appointment = $appointment;
        $contents = $appointment->contents->sortByDesc('created_at');

        $status_list = Appointment::STATUS_LIST;

        return view('appointments.show', compact('appointment', 'seller_appointment', 'contents'));
    }

    public function date_update(Appointment $appointment, Request $request)
    {
        $day = $request->day;
        $hour = $request->hour;

        if (!Appointment::isFuture($day, $hour)) {
            return back()->with('warning', '過去の指定はできません。');
        }

        $appointment = Appointment::find($request->appointment);
        $appointment->day = $day;
        $appointment->hour = $hour;
        $appointment->update();

        return redirect()->route('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment, Request $request)
    {

        return view('appointments.edit', compact('appointment'));
    }

    public function update(Appointment $appointment, Request $request)
    {
        $request->validate([
            'content' => 'required',
        ], [
            'content.required' => 'ヒアリング内容を入力してください。'
        ]);

        $user = Auth::user();

        $content = new Content();
        $content->appointment_id = $appointment->id;
        $content->user_id = $user->id;
        $content->content = $request->content;
        $content->save();

        return redirect()->route('appointments.show', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        //
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

        $status_list = Appointment::STATUS_LIST;

        return view('appointments.byday', compact('appointments', 'day', 'status_list'));
    }

    public function report(Request $request)
    {
        $appointment = Appointment::find($request->appointment);
        $status_list = Appointment::STATUS_LIST;

        return view('appointments.report', compact('appointment', 'status_list'));
    }

    public function change_status(Request $request)
    {
        $request->validate([
            'status' => 'numeric|min:1',
            'report' => 'required',
        ], [
            'status.min' => 'ステータスを変更してください。',
            'report.required' => '報告内容を入力してください。',
        ]);

        $appointment = Appointment::find($request->appointment);
        $appointment->status = $request->status;
        $appointment->report = $request->report;
        $appointment->update();

        $day = $appointment->day;

        return redirect()->route('appointments.byday', compact('day'));
    }
}
