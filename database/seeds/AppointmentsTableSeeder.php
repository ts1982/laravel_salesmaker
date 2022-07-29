<?php

use App\User;
use App\Appointment;
use App\Customer;
use App\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller_id = User::where('role', 'seller')->pluck('id');
        $appointer_id = User::where('role', 'appointer')->pluck('id');
        $customers = Customer::all();
        $time_zone = [10, 13, 16, 19];
        $start = Carbon::parse('2022-06-01');
        $end = Carbon::parse('2022-08-10');
        $position = [];
        $remain_position = [];

        while ($start < $end) {
            for ($i = 0; $i < 4; $i++) {
                $position[$start->format('Y-m-d')][$time_zone[$i]] = 0;
                $remain_position[$start->format('Y-m-d')][$time_zone[$i]] = 0;
                for ($j = 0; $j < $seller_id->count(); $j++) {
                    $rand = rand(1, 10);
                    if ($rand === 1) {
                        $remain_position[$start->format('Y-m-d')][$time_zone[$i]] += 1;
                    } else {
                        $position[$start->format('Y-m-d')][$time_zone[$i]] += 1;
                    }
                }
            }
            $start->addDay();
        }

        DB::beginTransaction();

        foreach ($position as $date => $time_zone) {
            foreach ($time_zone as $hour => $count) {
                for ($i = 0; $i < $count; $i++) {
                    $rand = rand(1, 10);
                    if ($rand === 1 || $rand === 2) {
                        $status = 3;
                    } else if ($rand === 4) {
                        $status = 1;
                    } else {
                        $status = 2;
                    }
                    $hasAppointment_seller_id = Appointment::where('day', $date)->where('hour', $hour)->pluck('seller_id');
                    $holiday_seller_id = Holiday::where('day', $date)->pluck('user_id');
                    $disabled_seller_id = $hasAppointment_seller_id->merge($holiday_seller_id);
                    $selected_id = $seller_id->diff($disabled_seller_id);
                    if (count($selected_id) > 0) {
                        $disabled_customer = Appointment::pluck('customer_id');
                        $customer_id = $customers->pluck('id');
                        $selectable_customer = $customer_id->diff($disabled_customer);
                        Appointment::create([
                            'day' => $date,
                            'hour' => $hour,
                            'content' => str_repeat("content ", 50),
                            'user_id' => $appointer_id->random(),
                            'seller_id' => $selected_id->random(),
                            'customer_id' => $selectable_customer->random(),
                            'status' => $status,
                            'report' => str_repeat("result ", 50),
                        ]);
                    }
                }
            }
        }

        $appointments = Appointment::where('status', 1)->get();
        foreach ($remain_position as $date => $time_zone) {
            foreach ($time_zone as $hour => $count) {
                if ($count !== 0) {
                    for ($i = 0; $i < $count; $i++) {
                        $hasAppointment_seller_id = Appointment::where('day', $date)->where('hour', $hour)->pluck('seller_id');
                        $holiday_seller_id = Holiday::where('day', $date)->pluck('user_id');
                        $disabled_seller_id = $hasAppointment_seller_id->merge($holiday_seller_id);
                        $selected_id = $seller_id->diff($disabled_seller_id);
                        if (count($selected_id) > 0) {
                            $selected_id = $selected_id->random();
                            $appointment = $appointments->where('day', '<', $date)->where('seller_id', $selected_id)->first();
                            if ($appointment) {
                                $rand = rand(1, 4);
                                if ($rand === 1) {
                                    $user_id = $appointment->user_id;
                                } else {
                                    $user_id = $selected_id;
                                }
                                $key = $appointments->search($appointment);
                                $appointments->forget($key);
                                if ($rand === 1) {
                                    $status = 3;
                                } else {
                                    $status = 2;
                                }
                                Appointment::create([
                                    'day' => $date,
                                    'hour' => $hour,
                                    'content' => str_repeat("content2 ", 50),
                                    'user_id' => $user_id,
                                    'seller_id' => $selected_id,
                                    'customer_id' => $appointment->customer_id,
                                    'status' => $status,
                                    'report' => str_repeat("result2 ", 50),
                                ]);
                            }
                        }
                    }
                }
            }
        }
        DB::commit();
    }
}
