<?php
namespace App\Components\DataFilters;
use App\Components\DataFilters\DataFilter;

class FilterFilter extends DataFilter{
 
    protected $sortingMethods=[
      'newest'=>'sort_by_newest',  
    ];
    public function q($key, $value){
      $this->builder->where(function($query) use($value){
        $value='%'.$value.'%';
        $query->where('name','like',$value)->orWhere('display_text','like',$value);
      });
    }    
    public function sort_by_newest(){
      $this->builer->orderBy('created_at','desc');  
    }
    public function defaultMethod($key, $value){
      
    }
}