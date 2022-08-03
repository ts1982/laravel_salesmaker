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

        $customers_id = Customer::pluck('id');
        $sort = $request->sort ?? '';
        $search = trim($request->search) ?? '';
        $seller_sort = $request->seller_sort ?? '';

        // 検索
        if ($request->search) {
            $customers_id = Customer::whereIn('id', $customers_id)->where('name', 'like', "%{$search}%")->pluck('id');
        }
        // ステータスソート
        if ($request->sort) {
            $latest_id = Appointment::selectRaw('max(id) as latest_id')->groupBy('customer_id')->pluck('latest_id');
            $sort_id = Appointment::whereIn('id', $latest_id)->where('status', $key)->pluck('customer_id');
            $customers_id = $customers_id->intersect($sort_id);
        }
        // 営業ソート
        if ($request->seller_sort) {
            if ($request->seller_sort === '-') {
                $seller_sort_id = Customer::where('user_id', null)->pluck('id');
                $customers_id = $customers_id->intersect($seller_sort_id);
            } else {
                $seller_sort_id = Customer::where('user_id', $request->seller_sort)->pluck('id');
                $customers_id = $customers_id->intersect($seller_sort_id);
            }
        }

        $customers = Customer::whereIn('id', $customers_id)->orderBy('id')->paginate(15)->onEachSide(1);
        $sellers = User::where('role', 'seller')->orderBy('id')->get();


        return view('dashboard.customers.index', compact('customers', 'search', 'sort_query', 'sort', 'sellers', 'seller_sort'));
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
        $sellers = User::where('role', 'seller')->orderBy('id')->get();

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

        $sellers = User::where('role', 'seller')->orderBy('id')->get();

        return view('dashboard.customers.replace', compact('customers', 'sellers'));
    }

    public function replace_view(Request $request)
    {
        $customers = Customer::whereIn('id', $request->customers_id)->orderBy('id')->paginate(50)->onEachSide(1);

        return view('dashboard.customers.replace_view', compact('customers'));
    }

    public function replace_store(Request $request)
    {
        if ($request->customers && $request->sellers) {
            $customers_id = $request->customers;
            // $customers = Customer::whereIn('id', $customers_id)->orderBy('id')->paginate(50)->onEachSide(1);
        } else if ($request->all && $request->sellers) {
            $customers_id = Customer::where('user_id', null)->orderBy('id')->pluck('id')->toArray();
            // $customers = Customer::where('user_id', null)->orderBy('id')->paginate(50)->onEachSide(1);
        } else {
            return redirect()->back()->with('warning', '顧客と営業担当者を選択してください。');
        }

        $seller_count = count($request->sellers);
        $i = rand(0, $seller_count - 1);
        foreach ($customers_id as $customer) {
            $i = $i % $seller_count;
            $customer = Customer::find($customer);
            $customer->user_id = $request->sellers[$i];
            $customer->save();
            $i++;
        }

        return redirect()->route('dashboard.customers.replace_view', compact('customers_id'));
        // return view('dashboard.customers.replace_view', compact('customers'));
    }
}
