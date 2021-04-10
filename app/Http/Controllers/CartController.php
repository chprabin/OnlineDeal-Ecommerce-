<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repos\ProductRepo;
use App\Components\Shop\Cart;
use Validator;

class CartController extends Controller
{

     public function index(Request $req){
        $cart=Cart::get();
        $products=$cart->initProducts();
        $view_data['cart']=$cart;
        return view('cart.cart',$view_data);
     }
     public function save(Request $req, ProductRepo $prepo){
        $product=$prepo->findOrFail($req->productId);
        $acceptable_quantities=range(1, $product->stock);
        $rules=[
            'productId'=>['required','exists:products,id'],
            'quantity'=>['required','integer','in:'.implode(',',$acceptable_quantities)],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails())
        {   
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $cart=Cart::get();
        if($cart->itemExists($data['productId'])){
            $cart->updateItem($data);
        }else{
            $cart->addItem($data);
        }
        if($req->ajax()){
            return json(['result'=>true, 'msg'=>'item added to cart.']);
        }
        return redirect()->route('cart');
     }

     public function update(Request $req, ProductRepo $prepo){
        $product=$prepo->findOrFail($req->productId);
        $acceptable_quantities=range(1, $product->stock);
        $rules=[
            'productId'=>['required','exists:products,id'],
            'quantity'=>['required','integer','in:'.implode(',',$acceptable_quantities)],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails())
        {   
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $cart=Cart::get();
        if($cart->itemExists($data['productId'])){
            $cart->updateItem($data);
        }else{
            $cart->addItem($data);
        }
        $itemIds=$cart->getItemIds();
        $view_data['cart']=$cart;
        if(count($itemIds)){
         $view_data['cart_products']=$prepo->getAllByAttributes(['id'=>$itemIds]);
        }
        $view=view('cart.cart-updatable')->with($view_data)->render();
        return json(['view'=>$view]);
     }

     public function delete(Request $req, ProductRepo $prepo){
        $productId=$req->productId;
        $cart=Cart::get();
        if($cart->removeItem($productId)){
         $view_data['cart']=$cart;
         $itemIds=$cart->getItemIds();
         if(count($itemIds)){
          $view_data['products']=$prepo->getAllByAttributes(['id'=>$itemIds]);
         }
         $view=view('cart.cart-updatable')->with($view_data)->render();
         return json(['result'=>true,'view'=>$view]);
        }
        return json(['result'=>false]);
     }
}
