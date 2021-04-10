<?php
namespace App\Components\DataFilters;
use App\Components\DataFilters\DataFilter;

class OrderFilter extends DataFilter{
 
    public function q($key,$value){
        $this->builder->where(function($q) use($value){
            $columns=['firstname','lastname','city','street','email'];
            foreach($columns as $c){
                $q->orWhere($c, 'like', '%'.$value.'%');
            }
        });
    }

    public function state($key,$value){
        $this->builder->whereHas('state',function($s) use($value){
            $s->where('name',$value);
        });
    }

    public function c($key, $value){
        $this->builder->whereHas('country',function($country) use($value){
            $country->where('name',$value);
        });
    }

    public function mintotal($key, $value){
        if(is_numeric($value)){
            $this->builder->where('total','>=',$value);
        }
    }
    
    public function maxtotal($key, $value){
        if(is_numeric($value)){
            $this->builder->where('total','<=',$value);
        }
    }
    
    public function defaultMethod($key, $value){
      
    }
}