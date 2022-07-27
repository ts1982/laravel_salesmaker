<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public static function getUsersRecord($role, $period)
    {
        if ($role === 'seller') {
            $sellers = User::where('role', 'seller')->orderBy('id')->get();
            $seller_list = [];

            foreach ($sellers as $seller) {
                $appointments = Appointment::where('seller_id', $seller->id)->where('day', 'like', "$period%")->orderBy('day', 'asc')->orderBy('hour', 'asc')->get();

                $total = $appointments->whereIn('status', [2, 3])->count();
                $contract_count = $appointments->where('status', 3)->count();
                if ($total !== 0) {
                    $rate = number_format($contract_count / $total * 100, 1);
                } else {
                    $rate = 0;
                }
                $rank = $seller->getRank($rate, 'seller');
                $incentive_rate = $seller->incentiveRate($rate);
                $incentive = User::SELLER_INCENTIVE_BASIC * $contract_count * $incentive_rate;

                $seller_list[$seller->id] = [$seller->name, $rank, $total, $contract_count, $rate, $incentive_rate, $incentive];
            }

            return $seller_list;
        }

        if ($role === 'appointer') {
            $appointers = User::where('role', 'appointer')->orderBy('id')->get();
            $appointer_list = [];

            foreach ($appointers as $appointer) {
                $appointments = Appointment::where('user_id', $appointer->id)->where('day', 'like', "$period%")->orderBy('day', 'asc')->orderBy('hour', 'asc')->get();

                $total = $appointments->whereIn('status', [2, 3])->count();
                $contract_count = $appointments->where('status', 3)->count();
                if ($total !== 0) {
                    $rate = number_format($contract_count / $total * 100, 1);
                } else {
                    $rate = 0;
                }
                $rank = $appointer->getRank($rate, 'appointer');
                $incentive_rate = $appointer->incentiveRate($rate);
                $incentive = User::APPOINTER_INCENTIVE_BASIC * $contract_count * $incentive_rate;

                $appointer_list[$appointer->id] = [$appointer->name, $rank, $total, $contract_count, $rate, $incentive_rate, $incentive];
            }

            return $appointer_list;
        }
    }
}
