<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $sort_query = Appointment::STATUS_LIST;
        $key = array_search($request->sort, $sort_query);

        $customers_id = Customer::where('user_id', $user->id)->pluck('id');

        if (User::roleIs('seller')) {
            $from_appointment_id = Appointment::where('seller_id', $user->id)->pluck('customer_id');
            $customers_id = $customers_id->merge($from_appointment_id);
        }
        if (User::roleIs('appointer')) {
            $from_appointment_id = Appointment::where('user_id', $user->id)->pluck('customer_id');
            $customers_id = $customers_id->merge($from_appointment_id);
        }
        $sort = $request->sort ?? '';
        $search = trim($request->search) ?? '';

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

        $customers = Customer::whereIn('id', $customers_id)->orderBy('id')->paginate(15)->onEachSide(1);

        return view('customers.index', compact('customers', 'search', 'sort_query', 'sort'));
    }

    public function show(Customer $customer)
    {
        $appointments = Appointment::where('customer_id', $customer->id)->orderBy('day')->orderBy('hour')->get();

        return view('customers.show', compact('customer', 'appointments'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Customer $customer, Request $request)
    {
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->tel = $request->tel;
        $customer->update();

        return redirect()->route('customers.show', compact('customer'));
    }
}
