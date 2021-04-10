<div class="cart-summary">
 <h3 class="no-margin">Cart summary</h3>
 <div style='margin:5px 0; border-bottom:1px solid #ccc; border-top:1px solid #ccc; padding:10px 0;'>
 
  <div style='margin-bottom:10px;'>
    <div class="inline-block" style='width:130px;'><span>Order subtotal: </span></div>   
    <div class="inline-block" style='width:130px;'><span>${{number_format($cart->getSubtotal(),2,'.','')}}</span></div>
  </div>
  
  <div style='margin-bottom:10px;'>
   <div class="inline-block" style='width:130px;'><span>Total items: </span></div>   
   <div class="inline-block" style='width:130px;'><span>{{$cart->getTotalItems()}} items</span></div>    
  </div>

  @if($cart->getDiscounts())
   <div style='margin-bottom:10px;'>
    <div class="inline-block" style='width:130px;'><span>Total discount: </span></div>   
    <div class="inline-block" style='width:130px;'><span>${{number_format($cart->getTotalDiscount(), 2, '.', '')}}</span></div>    
   </div>
  @endif
 </div>

  <div style='padding:10px 0;'>
   <div class='normal-bold'>
    <div class="inline-block" style='width:130px;'><span>Order total: </span></div>   
    <div class="inline-block" style='width:130px;'><span>${{number_format($cart->getOrderTotal(),2,'.','')}}</span></div>   
   </div>
  </div>

</div>