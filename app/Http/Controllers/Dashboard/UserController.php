<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Customer;
use App\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sellers_index()
    {
        $users = User::where('role', 'seller')->orderBy('id')->get();
        $role = '営業';

        return view('dashboard.users.index', compact('users', 'role'));
    }

    public function appointers_index()
    {
        $users = User::where('role', 'appointer')->orderBy('id')->get();
        $role = 'アポインター';

        return view('dashboard.users.index', compact('users', 'role'));
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
    public function show(User $user)
    {
        return view('dashboard.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('dashboard.users.show', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sellers_record(Request $request)
    {
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now();
            $period = $period->format('Y-m');
        }


        if ($request->user_id) {
            $user = User::find($request->user_id);
        } else {
            $user = User::find($request->user);
        }

        //
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

        return view('dashboard.users.record', compact('appointments', 'period', 'total', 'contract_count', 'rate', 'rank', 'sort_query', 'user'));
    }

    public function appointers_record(Request $request)
    {
        // 期間取得
        if ($request->period) {
            $period = $request->period;
        } else {
            $period = Carbon::now();
            $period = $period->format('Y-m');
        }

        if ($request->user_id) {
            $user = User::find($request->user_id);
        } else {
            $user = User::find($request->user);
        }

        //
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

        return view('dashboard.users.record', compact('appointments', 'period', 'total', 'contract_count', 'rate', 'rank', 'sort_query', 'user'));
    }
}
