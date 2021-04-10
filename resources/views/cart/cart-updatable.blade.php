@if($cart->getProducts()->count())
 <div class="row">
  <!-- items -->
   <div class="col col-lg-9 col-md-9 col-xs-12 col-sm-12">
    <div class="items">
     <div class="item-row title-row">
      <div class="item-cell title-cell"></div>
      <div class="item-cell title-cell">Product name</div>
      <div class="item-cell title-cell">Price</div>
      <div class="item-cell title-cell">Quantity</div>
      <div class="item-cell title-cell">Remove</div>
      <div class="item-cell title-cell">Item total</div>
     </div>
     @foreach($cart->getProducts() as $p)
      @php
       $item=$cart->findItemByProductId($p->id);
       $best_promo=$p->bestPromo()->first();
       $discounted_price=$p->calculateTotalDiscountedPrice();
      @endphp
      <div class="item-row">
       <div class="item-cell value-cell"><img src="{{$p->firstImage()->first()->image}}" class='item-image' alt=""></div>
       <div class="item-cell value-cell">
        <a href="{{route('product_details',['id'=>$p->id, 'name'=>$p->name])}}" class="normal-text">{{shorten_text($p->name, 100)}}</a>
       </div>
       <div class="item-cell value-cell">
         @if($best_promo)
          <div style='margin-bottom:10px;'>
           <span style='font-size:20px;color:brown;'>${{$discounted_price}}</span>
          </div>
          <div style='margin-bottom:10px;'>
           <del>
            <span style='font-size:20px;'>${{$p->price}}</span>
           </del>
          </div>
         @else
          <span style='font-size:20px; color:brown;'>${{$p->price}}</span>
         @endif
       </div>
       <div class="item-cell value-cell">
        <form action="{{route('cart_update')}}" class="update-form" method='POST'>
         @csrf
         @method('PUT')
         <input type="hidden" name='productId' value="{{$p->id}}">
         <select name="quantity" class='form-control sensor'>
          @foreach(range(1, $p->stock) as $r)
           @php
            $selected=$item->quantity==$r?"selected='selected'":null;
           @endphp
           <option value="{{$r}}" {{$selected}}>{{$r}}</option>
          @endforeach
         </select>
         <input type="submit" class='hide'>
        </form>
       </div>
       <div class="item-cell value-cell">
        <form action="{{route('cart_delete')}}" method='POST' class='delete-form'>
         @csrf
         @method('DELETE')
         <input type="hidden" name='productId' value="{{$p->id}}">
         <button type='submit' class='btn'><span class="fa fa-times"></span></button>
        </form>
       </div>
       <div class="item-cell value-cell">
        @php
         $item_total=$discounted_price > 0 ? $discounted_price * $item->quantity:
         $p->price * $item->quantity;
        @endphp
        <span style='color:brown; font-size:20px;'>${{number_format($item_total, 2, '.', '')}}</span>
       </div>
      </div>
     @endforeach
    </div>
   </div>
  <!-- end of items -->
  <!-- cart summary -->
    <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
     <div style='margin:20px 0;padding:10px 2%; background:#ededed; font-size:14px;'>
      @include('cart.cart-summary',['cart'=>$cart])
      <div style='margin:10px 0;'>
       <a href="{{route('order_checkout')}}" class='btn btn-shop'>Proceed to checkout</a>
       <a href="{{route('home')}}" class="btn">Continue shopping</a>
      </div>
     </div>
    </div>
  <!-- end of cart summary -->
 </div>
@else
 <div class="row">
  <div class="col col-12">
   <p class="text-center">Your shopping cart is empty</p>
  </div>
 </div>
@endif