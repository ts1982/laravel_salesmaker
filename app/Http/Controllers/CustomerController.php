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

        $latest_id = Appointment::selectRaw('max(id) as latest_id')->groupBy('customer_id')->pluck('latest_id');

        if ($request->sort && $request->search) { // ソート & 検索
            $sort = $request->sort;
            $search = trim($request->search);
            if (User::roleIs('appointer')) {
                $customers_id = Appointment::whereIn('id', $latest_id)->where('user_id', $user->id)->where('status', $key)->pluck('customer_id');
            } elseif (User::roleIs('seller')) {
                $customers_id = Appointment::whereIn('id', $latest_id)->where('seller_id', $user->id)->where('status', $key)->pluck('customer_id');
            }
            $customers = Customer::whereIn('id', $customers_id)->where('name', 'like', "%{$search}%")->orderBy('id')->paginate(15)->onEachSide(1);
        } else if ($request->sort) { // ソート
            if (User::roleIs('appointer')) {
                $customers_id = Appointment::whereIn('id', $latest_id)->where('user_id', $user->id)->where('status', $key)->pluck('customer_id');
            } elseif (User::roleIs('seller')) {
                $customers_id = Appointment::whereIn('id', $latest_id)->where('seller_id', $user->id)->where('status', $key)->pluck('customer_id');
            }
            $customers = Customer::whereIn('id', $customers_id)->orderBy('id')->paginate(15)->onEachSide(1);
            $sort = $request->sort;
            $search = '';
        } else if ($request->search) { // 検索
            $search = trim($request->search);
            $customers_id = Appointment::whereIn('id', $latest_id)->pluck('customer_id');
            $customers = Customer::whereIn('id', $customers_id)->where('name', 'like', "%{$search}%")->orderBy('id')->paginate(15)->onEachSide(1);
            $sort = '';
        } else {
            if (User::roleIs('appointer')) {
                $customer_id = Appointment::where('user_id', $user->id)->pluck('customer_id')->unique();
            } elseif (User::roleIs('seller')) {
                $customer_id = Appointment::where('seller_id', $user->id)->pluck('customer_id')->unique();
            }

            $customers = Customer::whereIn('id', $customer_id)->orderBy('id')->paginate(15)->onEachSide(1);
            $sort = '';
            $search = '';
        }


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
