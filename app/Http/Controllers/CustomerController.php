<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (User::roleIs('appointer')) {
            $customer_id = Appointment::where('user_id', $user->id)->pluck('customer_id')->unique();
        } else if (User::roleIs('seller')) {
            $customer_id = Appointment::where('seller_id', $user->id)->pluck('customer_id')->unique();
        }

        $customers = Customer::whereIn('id', $customer_id)->get();

        return view('customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }
}
