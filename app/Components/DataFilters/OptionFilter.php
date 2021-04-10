<?php
namespace App\Components\DataFilters;
use App\Components\DataFilters\DataFilter;

class OptionFilter extends DataFilter{
 
    protected $sortingMethods=[
      'newest'=>'sort_by_newest',  
    ];

    public function sort_by_newest(){
      $this->builer->orderBy('created_at','desc');  
    }
    public function defaultMethod($key, $value){
      
    }
}