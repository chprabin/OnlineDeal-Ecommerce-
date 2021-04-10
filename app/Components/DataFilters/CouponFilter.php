<?php
namespace App\Components\DataFilters;
use App\Components\DataFilters\DataFilter;

class CouponFilter extends DataFilter{
 
    public function q($key,$value){
     $this->builder->where('name','like','%'.$value.'%');
    }
    
    public function defaultMethod($key, $value){
      
    }
}