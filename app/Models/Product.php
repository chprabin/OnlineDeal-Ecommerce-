<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Components\Date;



class Product extends Model
{
    protected $fillable=['name','price','brandId','stock','desc'];

    public function images(){
        return $this->hasMany('App\Models\ProductImage','productId');
    }

    public function addImages(array $images){
     $images_array=[];
     foreach($images as $image){
        $image=(array)$image;
        array_push($images_array, $image);
     }
     return $this->images()->createMany($images_array);
    }
    
    public function brand(){
        return $this->belongsTo('App\Models\Brand','brandId');
    }

    public function categories(array $categoriesIds=[]){
        $relations=$this->belongsToMany('App\Models\Category',
        'product_categories','productId','categoryId');
        if(count($categoriesIds)){
            return $relations->find($categoriesIds);
        }
        return $relations;
    }
    
    public function addCategories(array $categoryIds=[]){
       foreach($categoryIds as $cid){
        try{
            $this->categories()->attach($cid);
        }catch(\Exception $ex){
            continue;
        }
       } 
    }
    public function firstImage()
    {
        return $this->images()->take(1);
    }
    public function options(){
        return $this->belongsToMany('App\Models\Option','product_options','productId','optionId');
    }
    public function addOptions(array $options=[]){
        foreach($options as $option){
            try{
                $this->options()->attach($option->id);
            }catch(\Exception $ex){
                continue;
            }   
        }
    }

    public function reviews(){
        return $this->hasMany('App\Models\Review','productId');
    }
    
    public function orderItems(){
        return $this->hasMany('App\Models\OrderItem','productId');
    }

    public function promos(){
        return $this->belongsToMany('App\Models\Promo','promo_products','productId','promoId');
    }
    public function bestPromo(){
        return $this->promos()->orderBy('amount','desc')->take(1);
    }
    public function activePromos(){
        return $this->promos()->where('exdate','>',Date::getNow());
    }
    public function calculateTotalDiscountedPrice(){
       $promos=$this->activePromos;
       $total_discount=0.00;
       foreach($promos as $p){
        if($p->type=='amount'){
         $total_discount+=$p->amount;
        }else if($p->type=='percent'){
         $total_discount+=$this->price * $p->amount/100;
        }
        return number_format(($this->price - $total_discount), 2, '.', '');
       } 
    }
}
