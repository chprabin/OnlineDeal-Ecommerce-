<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable=['userId','productId','title','review', 'rating'];

    public function product(){
        return $this->belongsTo('App\Models\Product','productId');
    }
    

    public function user(){
        return $this->belongsTo('App\User','userId');
    }
    
}

