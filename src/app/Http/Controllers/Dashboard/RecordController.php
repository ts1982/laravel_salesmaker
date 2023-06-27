<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Customer;
use App\Appointment;
use App\Record;
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
        $seller_list = Record::getUsersRecord('seller', $period);

        return view('dashboard.records.sellers', compact('seller_list', 'period'));
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
        $appointer_list = Record::getUsersRecord('appointer', $period);

        return view('dashboard.records.appointers', compact('appointer_list', 'period'));
    }

    public function incentive(Request $request)
    {
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now();
            $period = $period->format('Y-m');
        }

        // 営業
        $seller_list = Record::getUsersRecord('seller', $period);
        $seller_total = 0;
        foreach ($seller_list as $seller) {
            $seller_total += $seller[6];
        }

        // アポインター
        $appointer_list = Record::getUsersRecord('appointer', $period);
        $appointer_total = 0;
        foreach ($appointer_list as $appointer) {
            $appointer_total += $appointer[6];
        }

        return view('dashboard.records.incentive', compact('seller_list', 'appointer_list', 'period', 'seller_total', 'appointer_total'));
    }
}
