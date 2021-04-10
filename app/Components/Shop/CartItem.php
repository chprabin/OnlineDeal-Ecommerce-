<?php
namespace App\Components\Shop;

class CartItem
{
 public $productId=null;
 public $quantity=null;
 
 public function __construct($productId, $quantity){
   $this->productId=$productId;
   $this->quantity=$quantity; 
 }

}