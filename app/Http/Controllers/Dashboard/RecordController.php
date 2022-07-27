<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Customer;
use App\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RecordController extends Controller
{
    public function sellers(Request $request)
    {
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now();
            $period = $period->format('Y-m');
        }

        //
        $users = User::where('role', 'seller')->orderBy('id')->get();
        $user_list = [];

        foreach ($users as $user) {
            $appointments = Appointment::where('seller_id', $user->id)->where('day', 'like', "$period%")->orderBy('day', 'asc')->orderBy('hour', 'asc')->get();

            $total = $appointments->whereIn('status', [2, 3])->count();
            $contract_count = $appointments->where('status', 3)->count();
            if ($total !== 0) {
                $rate = number_format($contract_count / $total * 100, 1);
            } else {
                $rate = 0;
            }
            $rank = $user->getRank($rate, 'seller');
            $user_list[$user->id] = [$user->name, $total, $contract_count, $rate, $rank];
        }

        return view('dashboard.records.sellers', compact('user_list', 'period'));
    }

    public function appointers(Request $request)
    {
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now();
            $period = $period->format('Y-m');
        }

        //
        $users = User::where('role', 'appointer')->orderBy('id')->get();
        $user_list = [];

        foreach ($users as $user) {
            $appointments = Appointment::where('user_id', $user->id)->where('day', 'like', "$period%")->orderBy('day', 'asc')->orderBy('hour', 'asc')->get();

            $total = $appointments->whereIn('status', [2, 3])->count();
            $contract_count = $appointments->where('status', 3)->count();
            if ($total !== 0) {
                $rate = number_format($contract_count / $total * 100, 1);
            } else {
                $rate = 0;
            }
            $rank = $user->getRank($rate, 'appointer');
            $user_list[$user->id] = [$user->name, $total, $contract_count, $rate, $rank];
        }

        return view('dashboard.records.appointers', compact('user_list', 'period'));
    }
}
