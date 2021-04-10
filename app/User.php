<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Components\Date;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function roles(){
        return $this->belongsToMany('App\Models\Role','user_roles','userId','roleId');
    }
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
    public function isAdmin(){
        return $this->roles->pluck('role')->contains('admin');
    }
    public function coupons(){
        return $this->belongsToMany('App\Models\Coupon','user_coupons','userId','couponId');
    }

    public function wishedProducts(){
        return $this->belongsToMany('App\Models\Product','wishes','userId','productId');
    }

    public function addToWishes(array $productIds){
        foreach($productIds as $pid){
            try{
                $this->wishedProducts()->attach($pid);
            }catch(\Exception $ex){
                continue;
            }
        }
    }

    public function removeFromWishes(array $productIds){
        return $this->wishedProducts()->detach($productIds);
    }
    public function reviews(){
        return $this->belongsToMany('App\Models\Product','reviews','userId','productId');
    }

    public static function getStats($month=null){
        $query=User::join('orders','orders.userId','=','users.id')->
        select(DB::raw('count(distinct users.id) as total_users, sum(orders.total) as orders_total'));
        if($month){
            $first_day_date=Date::buildFirstDayDate($month);
            if($first_day_date){
                $query->where('users.created_at','>=',$first_day_date);
            }
            $last_day_date=Date::buildLastDayDate($month);
            if($last_day_date){
                $query->where('users.created_at','<=',$last_day_date);
            }
        }
        return $query->first();
    }
}
