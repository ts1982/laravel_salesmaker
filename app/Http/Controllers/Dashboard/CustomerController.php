<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\Customer;
use App\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $user = Auth::user();

        $sort_query = Appointment::STATUS_LIST;

        if ($request->sort) { // ソート
            $key = array_search($request->sort, $sort_query);
            $latest_id = Appointment::selectRaw('max(id) as latest_id')->groupBy('customer_id')->pluck('latest_id');
            $customers_id = Appointment::whereIn('id', $latest_id)->where('status', $key)->pluck('customer_id');
            // if (User::roleIs('appointer')) {
            //     $customers_id = Appointment::whereIn('id', $latest_id)->where('user_id', $user->id)->where('status', $key)->pluck('customer_id');
            // } elseif (User::roleIs('seller')) {
            //     $customers_id = Appointment::whereIn('id', $latest_id)->where('seller_id', $user->id)->where('status', $key)->pluck('customer_id');
            // }
            $customers = Customer::whereIn('id', $customers_id)->orderBy('id')->paginate(15)->onEachSide(1);
            $keyword = '';
        } else if ($request->has('search')) { // 検索
            $keyword = trim($request->search);
            $customers = Customer::where('name', 'like', "%{$keyword}%")->orderBy('id')->paginate(15)->onEachSide(1);
        } else {
            // if (User::roleIs('appointer')) {
            //     $customer_id = Appointment::where('user_id', $user->id)->pluck('customer_id')->unique();
            // } elseif (User::roleIs('seller')) {
            //     $customer_id = Appointment::where('seller_id', $user->id)->pluck('customer_id')->unique();
            // }

            $customers = Customer::orderBy('id')->paginate(15)->onEachSide(1);
            $keyword = '';
        }

        return view('dashboard.customers.index', compact('customers', 'keyword', 'sort_query'));
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
