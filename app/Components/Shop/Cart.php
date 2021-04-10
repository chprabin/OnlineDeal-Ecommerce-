<?php
namespace App\Components\Shop;
use App\Models\Product;
use App\Components\Shop\CartItem;
use App\Models\Coupon;

class Cart
{
 private static $ins=null;
 private $products=null;

 private function __construct($init_products=true){
  $this->products=collect([]);
  if($init_products){
    $this->initProducts();
  }  
 }

 public function getProducts(){
   if(!$this->products->count()){
    $this->initProducts();
   }
   return $this->products;
 }
 
 public static function get($init_products=true){
  if(static::$ins==null){
    static::$ins=new Cart($init_products);
  }
  return static::$ins;
 }

 public function getTotalItems(){
   $out=0;
   foreach($this->getProducts() as $product){
    $item=$this->findItemByProductId($product->id);
    $out+=$item->quantity;
   }
   return $out;
 }

 public function itemExists($productId){
  $items=$this->getItems();
  if($items && count($items)){
    foreach($items as $item){
      if($item->productId==$productId){
        return true;
      }
    }
  }
  return false;
 }

 public function getSubtotal(){
  $out=0.00;
  foreach($this->getProducts() as $product){
    $item=$this->findItemByProductId($product->id);
    $price=$product->calculateTotalDiscountedPrice();
    $out+=($price * $item->quantity);
  }   
  return $out;
 }

 public function addItem(array $data){
  $cart_item=new CartItem($data['productId'], $data['quantity']);
  $items=$this->getItems();
  if(!$items){
    $items=[];
  }
  array_push($items, $cart_item);
  $this->updateItems($items);
 }

 public function updateItem(array $data){
  $items=$this->getItems();
  if($items && count($items)){
   $productId=$data['productId']; 
   foreach($items as $key=>$item){
    if($productId==$item->productId){   
     $item->quantity=$data['quantity']; 
     $items[$key]=$item;
     $this->updateItems($items);
     return true;
    }
   }
  }
  return false;
 }

 public function clear(){
  $this->products=collect([]);
  session()->forget(['cart_items','cart_discounts']);
  return $this; 
 }

 public function getDiscounts(){
  $out=session('cart_discounts');
  if(!$out){
    $out=[];
  }
  return $out;
 }

 public function getOrderTotal(){
  $test_mode=config('settings')['test_mode'];
  if($test_mode){
   return 0.01;
  } 
  $sub_total=$this->getSubtotal();
  $total_discount=$this->getTotalDiscount();
  return ($sub_total-$total_discount);
 }

 public function getTotalDiscount(){
  $sub_total=$this->getSubtotal();
  $total_discount=0.00;
  foreach($this->getDiscounts() as $discount){
    $reduction=0.00;
    if($discount->type=='amount'){
     $reduction=$discount->amount;
    }else if($discount->type=='percent'){
     $reduction=($discount->amount/100)*$sub_total;
    }
    $total_discount+=$reduction;
  } 
  return $total_discount;
 }

 public function applyDiscount(Coupon $coupon){
  $discounts=$this->getDiscounts();
  array_push($discounts, $coupon);
  $this->updateDiscounts($discounts);
  return $this;
 }

 public function updateDiscounts(array $discounts){
  session(['cart_discounts'=>$discounts]);
  return $this;
 }
 public function removeItem($productId){
  $items=$this->getItems();
  if($items && count($items)){
    foreach($items as $key=>$item){
      if($productId==$item->productId){
        $products=$this->getProducts();
        if($products && $products->count()){
         $this->products=$products->filter(function($product) use($productId){
          return $product->id!=$productId;
         });   
        }
        $out=clone $item;
        unset($items[$key]);
        $this->updateItems($items);
        return $out;  
      }
    }
  }
  return false;
 }

 public function updateItems($items){
  session(['cart_items'=>$items]);
  return $this;
 }

 public function findItemByProductId($productId){
  $items=$this->getItems();
  if($items && count($items)){
    foreach($items as $item){
      if($item->productId==$productId){
        return $item;
      }
    }
  }
  return null;
 }

 public function initProducts(){
  $items=$this->getItems();
  if($items && count($items)){
    $prpeo=resolve('App\Repos\ProductRepo');
    $products=$prpeo->with(['firstImage','activePromos','bestPromo'])->
    getAllByAttributes(['id'=>$this->getItemIds()]);
    $this->products=$products;
  }
  return $this;
 }
 public function getItems(){
   return session('cart_items');
 }
 public function getItemIds()
 {
   $items=$this->getItems();
   if($items && count($items)){
    $out=[];
    foreach($items as $item){
      array_push($out, $item->productId);
    }
    return $out;
   }
   return [];
 }

 public function changeProductsState(){
   foreach($this->getProducts() as $product){
    $item=$this->findItemByProductId($product->id);
    $product->stock-=$item->quantity;
    $product->sold_count+=$item->quantity;
    $product->save();
   }
   return $this;
 }
}