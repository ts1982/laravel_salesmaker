<?php

namespace App;

use App\Appointment;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    private const SELLER_RANK_LIST = ['A' => 25, 'B' => 20, 'C' => 15, 'D' => 0];
    private const APPOINTER_RANK_LIST = ['A' => 25, 'B' => 20, 'C' => 15, 'D' => 0];
    const INCENTIVE_RATE = ['A' => 100, 'B' => 80, 'C' => 50, 'D' => 20];
    const SELLER_INCENTIVE_BASIC = 20000;
    const APPOINTER_INCENTIVE_BASIC = 5000;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }

    public function customers()
    {
        return $this->hasMany('App\Customer');
    }

    public function content()
    {
        return $this->belongsTo('App\Content');
    }

    public static function roleIs($role)
    {
        $user = Auth::user();

        if ($role === $user->role) {
            return true;
        }

        return false;
    }

    public function sales()
    {
        $sales = Appointment::where('seller_id', $this->id)->orderBy('day', 'desc')->orderBy('hour', 'desc')->get();

        return $sales;
    }

    public function getRank($rate, $role)
    {
        if ($role === 'seller') {
            $list = self::SELLER_RANK_LIST;
        }
        if ($role === 'appointer') {
            $list = self::APPOINTER_RANK_LIST;
        }

        if ($rate >= $list['A']) {
            return 'A';
        } elseif ($list['A'] > $rate && $rate >= $list['B']) {
            return 'B';
        } elseif ($list['B'] > $rate && $rate >= $list['C']) {
            return 'C';
        } elseif ($list['C'] > $rate) {
            return 'D';
        }
    }

    public function incentiveRate($rate)
    {
        $incentive_rate = self::INCENTIVE_RATE[$this->getRank($rate, $this->role)] / 100;

        return $incentive_rate;
    }

    public function userHasHoliday($day)
    {
        $exists = Holiday::where('user_id', $this->id)->where('day', $day)->count();
        if ($exists === 1) {
            return true;
        } else {
            return false;
        }
    }

    public function hasHolidaysOutOfRange($start, $end)
    {
        $holidays = Holiday::where('user_id', $this->id)->get();
        if (!$holidays->contains('day', '<', $start) && $end === null) {
            return false;
        } else if ($holidays->contains('day', '<', $start) || $holidays->contains('day', '>', $end)) {
            return true;
        } else {
            return false;
        }
    }

    public function sellerHasAppointmentsOutOfRange($start, $end)
    {
        $appointments = Appointment::where('seller_id', $this->id)->get();
        if (!$appointments->contains('day', '<', $start) && $end === null) {
            return false;
        } else if ($appointments->contains('day', '<', $start) || $appointments->contains('day', '>', $end)) {
            return true;
        } else {
            return false;
        }
    }

    public static function sellersInOperate($day)
    {
        $sellers_in_operate_1 = User::where('role', 'seller')->where('end', '>=', date('Y-m-d', strtotime($day)))->where('start', '<=', date('Y-m-d', strtotime($day)))->get();
        $sellers_in_operate_2 = User::where('role', 'seller')->where('end', null)->where('start', '<=', date('Y-m-d', strtotime($day)))->get();

        $sellers_in_operate = $sellers_in_operate_1->merge($sellers_in_operate_2);

        return $sellers_in_operate;
    }

    public static function countSellersInOperate($day)
    {
        $has_started = User::where('role', 'seller')->where('start', '<=', date('Y-m-d', strtotime($day)))->count();
        $has_ended = User::where('role', 'seller')->where('end', '<', date('Y-m-d', strtotime($day)))->count();
        $count = $has_started - $has_ended;

        return $count;
    }

    public function betweenStartAndEnd($day)
    {
        if ($this->end !== null) {
            if ($this->start <= date('Y-m-d', strtotime($day)) && $this->end >= date('Y-m-d', strtotime($day))) {
                return true;
            } else {
                return false;
            }
        } else if ($this->start !== null) {
            if ($this->start <= date('Y-m-d', strtotime($day))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
