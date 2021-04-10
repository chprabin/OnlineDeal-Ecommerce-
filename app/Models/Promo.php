<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable=['name','amount','type','exdate'];

    public function products(){
        return $this->belongsToMany('App\Models\Product','promo_products','promoId','productId');
    }

    public function addProducts(array $productIds){
        foreach($productIds as $pid){
            try{    
                $this->products()->attach($pid);
            }catch(\Exception $ex){
                continue;
            }
        }
    }

    public function calculateDiscountedPrice($amount)
    {
      $out=0.00;  
      if($this->type=='amount'){
        $out=$amount - $this->amount;
      }else if($this->type=='percent'){
        $out=$amount - ($this->amount/100)*$amount;
      }
      return number_format($out,2,'.','');
    }
}
