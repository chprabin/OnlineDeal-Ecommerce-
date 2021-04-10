<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Components\Date;

class Coupon extends Model
{
    protected $fillable=['name','type','code','active',
    'amount','sdate','edate','min_total','max_usage'];

    protected static function boot(){
     parent::boot();
     static::creating(function($q){
        $q->code=create_coupon_code();
     });   
    }

    public function users(){
        return $this->belongsToMany('App\User','user_coupons','couponId','userId');
    }

    public function isActive(){
        return $this->active && (Date::isFuture($this->edate) && Date::isPast($this->sdate));
    }
}
