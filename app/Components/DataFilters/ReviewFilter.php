<?php
namespace App\Components\DataFilters;
use App\Components\DataFilters\DataFilter;

class ReviewFilter extends DataFilter{
 
    protected $sortingMethods=[
      'newest'=>'sort_by_newest', 
      'top_rated'=>'sort_by_top_rated', 
    ];
    public function q($key, $value){
      $this->builder->where(function($q) use($value){
        $value='%'.$value.'%';
        $q->where('title','like',$value)->orWhere('review','like',$value);
      });
    }    

    public function sort_by_top_rated(){
      $this->builder->orderBy('rating','desc');
    }
    public function sort_by_newest(){
      $this->builer->orderBy('created_at','desc');  
    }
    public function defaultMethod($key, $value){
      
    }
    public function rating_star($key,$value){
      $this->builder->where(function($q) use($value){
        $values=explode(';',$value);
        foreach($values as $v){
          $q->orWhere('rating',$v);
        }
      }); 
    }
    public function productId($key, $value){
      $this->builder->whereHas('product',function($product) use($value){
        $product->where('id',$value); 
      });
    }
}