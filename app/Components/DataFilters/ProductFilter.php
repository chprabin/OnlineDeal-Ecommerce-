<?php
namespace App\Components\DataFilters;
use App\Components\DataFilters\DataFilter;
use Illuminate\Support\Facades\DB;

class ProductFilter extends DataFilter{
 
    protected $sortingMethods=[
      'newest'=>'sort_by_date_desc',
      'oldest'=>'sort_by_date',
      'most_expensive'=>'sort_by_most_expensive',
      'cheapest'=>'sort_by_cheapest',
      'sold_count_desc'=>'sort_by_sold_count_desc',
      'most_popular'=>'sort_by_most_popular',
      'most_wished'=>'sort_by_most_wished',  
    ];
    protected $nutral_params=[
      'wcrud','wselect','ws','wps','insertedData',
      'view','wm','page','per_page','inserted_data',
    ];
    public function q($key, $value)
    { 
      $this->builder->where(function($q) use($value){
        $value='%'.$value.'%';
        $q->where('name','like',$value)->orWhere('desc','like',$value);
      });
    }
    public function sq($key, $value)
    { 
      $this->builder->where(function($q) use($value){
        $value='%'.$value.'%';
        $q->where('name','like',$value)->orWhere('desc','like',$value);
      });
    }
    public function sort_by_date_desc(){
      $this->builder->orderBy('created_at','desc');  
    }

    public function sort_by_date(){
      $this->builder->orderBy('created_at','asc');
    }
    public function sort_by_sold_count_desc(){
      $this->builder->orderBy('sold_count','desc');
    }
    public function sort_by_most_popular(){
      $this->builder->join('reviews','reviews.productId','=','products.id')->groupBy('reviews.productId')->
      select(DB::raw('products.*, avg(rating) as average_rating'))->orderBy('average_rating','desc');
    }
    public function sort_by_most_expensive(){
      $this->builder->orderBy('price','desc');
    }
    public function sort_by_cheapest(){
      $this->builder->orderBy('price','asc');
    }
    public function sort_by_most_wished(){
      $this->builder->join('wishes','products.id','=','wishes.productId')->groupBy('wishes.productId')->
      select(DB::raw('products.*, count(wishes.userId) as userCount'))->orderBy('userCount','desc');
    }
    public function defaultMethod($key, $value){
      $this->builder->whereHas('options',function($options) use($key, $value){
        $options->whereHas('filter',function($filter) use($key){
          $filter->where('url_slug',$key);
        });
        $values=explode(';',$value);
        $options->where(function($options) use($values){
          foreach($values as $v){
            if(!empty($v))
             $options->orWhere('value',$v);
          }
        });
      });   
    }

    public function c($key, $value){
      $this->builder->whereHas('categories',function($categories) use($value){
        $categories->where('showname',$value);
      });
    }

    public function minprice($key, $value){
     if(is_numeric($value)){
      $this->builder->where('price','>=',$value);
     }
    }

    public function maxprice($key, $value){
      if(is_numeric($value)){
        $this->builder->where('price','<=',$value);
      }
    }

    public function availability($key, $value){
      $value=strtolower($value);
      if($value=='in_stock'){
        $this->builder->where('stock','>','0');
      }else if($value=='out_of_stock'){
        $this->builder->where('stock','=','0');
      }
    }
    
    public function rating_star($key, $value){
      $this->builder->whereHas('reviews',function($reviews) use($value){
        $values=explode(';',$value);
        $reviews->where(function($q) use($values){
          foreach($values as $v){
            $q->orWhere('rating',$v);
          }
        });        
      });
    }
}