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
        $sort_query = Appointment::STATUS_LIST;

        $key = array_search($request->sort, $sort_query);

        $latest_id = Appointment::selectRaw('max(id) as latest_id')->groupBy('customer_id')->pluck('latest_id');

        if ($request->sort && $request->search) { // ソート & 検索
            $sort = $request->sort;
            $search = trim($request->search);
            $customers_id = Appointment::whereIn('id', $latest_id)->where('status', $key)->pluck('customer_id');
            $customers = Customer::whereIn('id', $customers_id)->where('name', 'like', "%{$search}%")->orderBy('id')->paginate(15)->onEachSide(1);
        } else if ($request->sort) { // ソート
            $customers_id = Appointment::whereIn('id', $latest_id)->where('status', $key)->pluck('customer_id');
            $customers = Customer::whereIn('id', $customers_id)->orderBy('id')->paginate(15)->onEachSide(1);
            $sort = $request->sort;
            $search = '';
        } else if ($request->search) { // 検索
            $search = trim($request->search);
            $customers_id = Appointment::whereIn('id', $latest_id)->pluck('customer_id');
            $customers = Customer::whereIn('id', $customers_id)->where('name', 'like', "%{$search}%")->orderBy('id')->paginate(15)->onEachSide(1);
            $sort = '';
        } else {
            $customer_id = Appointment::pluck('customer_id')->unique();
            $customers = Customer::whereIn('id', $customer_id)->orderBy('id')->paginate(15)->onEachSide(1);
            $sort = '';
            $search = '';
        }

        $sellers = User::where('role', 'seller')->get();

        return view('dashboard.customers.index', compact('customers', 'search', 'sort_query', 'sort', 'sellers'));
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
    public function show(Customer $customer)
    {
        $appointments = Appointment::where('customer_id', $customer->id)->orderBy('day')->orderBy('hour')->get();

        return view('dashboard.customers.show', compact('customer', 'appointments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $sellers = User::where('role', 'seller')->get();

        return view('dashboard.customers.edit', compact('customer', 'sellers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Customer $customer, Request $request)
    {
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->tel = $request->tel;
        $customer->user_id = $request->user_id;
        $customer->update();

        return redirect()->route('dashboard.customers.show', compact('customer'));
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

    public function replace(Request $request)
    {
        if ($request->sort) { // ソート
            if ($request->sort === 'all') {
                $customers = Customer::orderBy('id')->paginate(50)->onEachSide(1);
            } else {
                $customers = Customer::where('user_id', $request->sort)->orderBy('id')->paginate(50)->onEachSide(1);
            }
        } else {
            $customers = Customer::where('user_id', null)->orderBy('id')->paginate(50)->onEachSide(1);
        }

        $sellers = User::where('role', 'seller')->get();

        return view('dashboard.customers.replace', compact('customers', 'sellers'));
    }

    public function replace_store(Request $request)
    {
        if ($request->customers && $request->sellers) {
            $customers_id = $request->customers;
            $seller_count = count($request->sellers);
            $i = rand(0, $seller_count - 1);
            foreach ($customers_id as $customer) {
                $i = $i % $seller_count;
                $customer = Customer::find($customer);
                $customer->user_id = $request->sellers[$i];
                $customer->save();
                $i++;
            }

            $customers = Customer::whereIn('id', $customers_id)->paginate(50)->onEachSide(1);
        } else {
            return redirect()->back()->with('warning', '顧客と営業担当者を選択してください。');
        }

        $sellers = User::where('role', 'seller')->get();

        return view('dashboard.customers.replace_view', compact('customers', 'sellers'));
    }
}
