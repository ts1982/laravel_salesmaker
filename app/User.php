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

    public function getRank($rate)
    {
        if (self::roleIs('seller')) {
            $list = self::SELLER_RANK_LIST;
        }
        if (self::roleIs('appointer')) {
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
}
