<?php
namespace App\Components\DataFilters;
use App\Components\DataFilters\DataFilter;

class HcFilter extends DataFilter{
 
    public function q($key,$value){
        $this->builder->where(function($q) use($value){
            $value='%'.$value.'%';
            $q->where('name','like',$value)->orWhere('content','like',$value);    
        });
    }
    
    public function defaultMethod($key, $value){
      
    }
}