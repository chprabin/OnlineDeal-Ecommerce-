<?php
namespace App\Components\Shop;
use Illuminate\Support\Facades\DB;

class Wish{
    private $products=null;
    private static $ins=null;

    private function __construct(){
        $this->products=collect([]);
    }

    public static function get(){
        if(static::$ins==null){
            static::$ins=new Wish();
        }
        return static::$ins;
    }   
    public function getProducts(){
       $products=$this->products;
       if(count($products)){
        return $products;
       } 
       if(auth()->check()){
        $products=auth()->user()->wishedProducts()->with(['bestPromo','activePromos'])->withCount([
          'reviews as total_reviews',
          'reviews as total_ratings'=>function($q){
           $q->select(DB::raw('sum(rating)'));
          }
        ])->get();
       }else{
        $items=$this->getItems();
        if(count($items)){
         $prepo=resolve('App\Repos\ProductRepo');
         $products=$prepo->withCount([
           'reviews as total_reviews',
           'reviews as total_ratings'=>function($q){
            $q->select(DB::raw('sum(rating)'));
           }
         ])->getAllByAttributes(['id'=>$items]);
        }   
       }
       $this->products=$products;
       return $products;
    }
    public function addToList($productId){
      if(auth()->check()){
        $user=auth()->user();
        return $user->addToWishes([$productId]);
      }
      $items=$this->getItems();
      foreach($items as $key=>$pid){
        if($pid==$productId)
         return false;
      }
      array_push($items, $productId);
      $this->updateItems($items);
      return true;
    }
    public function updateItems(array $items)
    {   
        session(['wish_items'=>$items]);
        return $this;
    }
    public function getTotalItems(){
      if(auth()->check()){
        return auth()->user()->wishedProducts()->count();
      }  
      return count($this->getItems());
    }
    public function removeFromList($productId){
        if(auth()->check()){
            return auth()->user()->removeFromWishes([$productId]);
        }
        $items=$this->getItems();
        foreach($items as $key=>$pid){
            if($productId==$pid){
                unset($items[$key]);
                $this->updateItems($items);
                return true;
            }
        }
        return false;
    }
    public function getItems(){
        $items=session('wish_items');
        if(!$items){
          $items=[];  
        }
        return $items;
    }
}