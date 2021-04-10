@extends('layouts.default')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1>Shopping cart</h1>
  </div>
 </div>
 
 <div class="shop-list">
  <div class="cart-updatable">
   @include('cart.cart-updatable',['cart'=>$cart])
  </div>
 </div>
 @include('partials.personalized-recommendations-slider')
@endsection

@section('js')
 <script>
  init_cart_update_forms();
  init_cart_delete_forms();

  function init_cart_update_forms(){
    var update_forms=document.querySelectorAll('.shop-list form.update-form');
    update_forms.forEach(function(form){
     createComponent('ajaxform',{},function(af){
      af.setForm(form);
      af.onBeforeSend(function(){
        addAjaxLoader();
        return true;
      });
      af.onSend(function(f,r){
        if(r.view){
          $('.shop-list .cart-updatable').html(r.view);
          init_cart_update_forms();
          init_cart_delete_forms();
        }
        removeAjaxLoader();
      });
      af.init();
     });
    });
  }
  function init_cart_delete_forms(){
    var delete_forms=document.querySelectorAll('.shop-list form.delete-form');
    delete_forms.forEach(function(form){
      createComponent('ajaxform',{},function(af){
        af.setForm(form);
        af.onBeforeSend(function(){
          addAjaxLoader();
          return true;
        });
        af.onSend(function(f,r){
         if(r.view){
          $('.shop-list .cart-updatable').html(r.view);
          init_cart_delete_forms();
          init_cart_update_forms();
         } 
         removeAjaxLoader();
        });
        af.init();
      });
    });    
  }
 </script>
@endsection