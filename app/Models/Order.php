<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Components\Shop\Cart;

class Order extends Model
{
  protected $fillable=['email','firstname','lastname','userId','countryId','city','street','phone'
 ,'created_at','updated_at','payment_batch_number','payeer_account','payee_account','stateId','total'];

  public function state(){
     return $this->belongsTo('App\Models\OrderState','stateId'); 
  }

  protected static function boot(){
      parent::boot();
      static::creating(function($q){
        $q->stateId=1;
      });
  }

  public function user(){
    return $this->belongsTo('App\User','userId');     
  }

  public function country(){
    return $this->belongsTo('App\Models\Country','countryId');    
  }

  public function items(){
    return $this->hasMany('App\Models\OrderItem','orderId');
  }

  public function addItems(Cart $cart)
  {
   $order_items=[];
   foreach($cart->getProducts() as $product){
    $item=$cart->findItemByProductId($product->id);
    $order_item=['productId'=>$product->id, 'orderId'=>$this->id, 'quantity'=>$item->quantity];
    array_push($order_items, $order_item);
   }
   if(count($order_items)){
    $this->items()->insert($order_items);
    return true;
   }
   return false;
  }
}
