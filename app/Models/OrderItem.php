<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable=['quantity','productId','orderId'];

    public function product(){
        return $this->belongsTo('App\Models\Product','productId');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order','orderId');        
    }
}
