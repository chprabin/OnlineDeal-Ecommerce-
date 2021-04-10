<?php
namespace App\Components\DataFilters;
use App\Components\DataFilters\DataFilter;

class OrderItemFilter extends DataFilter{
 
    public function q($key,$value){
      $this->builder->whereHas('product',function($product) use($value){
        $product->where(function($product) use($value){
           $columns=['name','desc'];
           foreach($columns as $c){
            $product->orWhere($c,'like','%'.$value.'%');
           } 
        });
      });  
    }
    
    public function defaultMethod($key, $value){
      
    }
}