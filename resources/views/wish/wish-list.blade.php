@extends('layouts.default')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="">Wish list</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <div class="wish-list shop-list">
    @if($wish->getProducts()->count())
     <div class="items">
      <div class="item-row titlg-row">
       <div class="item-cell title-cell"></div>
       <div class="item-cell title-cell">Product name</div>
       <div class="item-cell title-cell">Product price</div>
       <div class="item-cell title-cell">Product availability</div>
       <div class="item-cell title-cell">Product rating</div>
       <div class="item-cell title-cell">Actions</div>
      </div>
      @foreach($wish->getProducts() as $p)
      @php
       $discounted_price=$p->calculateTotalDiscountedPrice();
      @endphp
       <div class="item-row">
        <div class="item-cell value-cell"><img src="{{asset($p->images[0]->image)}}" class='item-image' alt=""></div>
        <div class="item-cell value-cell">
         <a href="{{route('product_details',['id'=>$p->id, 'name'=>$p->name, 'wm'=>1, 'view'=>'quicklook'])}}" 
         class="a-normal" onclick='event.preventDefault(); view_product_details(this.getAttribute("href"));'>
          {{shorten_text($p->name,100)}}
         </a>
        </div>
        <div class="item-cell value-cell">
         @if($discounted_price > 0)
          <div class="inline-block">
            <del>
             <span style='font-size:20px;'>${{number_format($p->price,2,'.','')}}</span>
            </del>        
          </div>
          <div class="inline-block">
           <span style='font-size:20px;color:brown;'>${{number_format($discounted_price,2,'.','')}}</span>
          </div>
         @else
          <div class="inline-block">
           <span style='font-size:20px; color:brown;'>${{number_format($p->price,2,'.','')}}</span>
          </div>
         @endif
        </div>
        <div class="item-cell value-cell">
         @php
          $text='available';
          if($p->stock==0){
            $text='Product is out of stock';
          }else if($p->stock<=20){
            $text='Only '.$p->stock.' is left in stock. order soon.';
          }
         @endphp
         <span style='color:brown; font-size:18px; '>{{$text}}</span>
        </div>
        <div class="item-cell value-cell">
         @if(!$p->total_reviews)
          @php
           $average_rating=calculate_average_rating($p->total_ratings, $p->total_reviews);
          @endphp
          <div class="dropdown">
           <a href="#" class="dropdown-toggle">@include('partials.rating-stars',
           ['average_rating'=>$average_rating]) <i class="fa fa-caret-down"></i></a>
           <ul class="dropdown-menu">
            <div style='width:300px; padding:10px;'>
             <div style='margin:10px 0;;'>{{$average_rating}} out of 5 stars</div>
             @include('partials.product-rating-summary',['product'=>$p])
            </div>    
           </ul>
          </div>
         @else 
          <span>-</span>
         @endif
        </div>
        <div class="item-cell value-cell">
         <div class="inline-block">
          <form action="{{route('cart_save')}}" method='POST'>
           @csrf
           <input type="hidden" name='productId' value='{{$p->id}}'>
           <input type="hidden" name='quantity' value='1'>
           <input type="submit" value='Add to cart' class='btn btn-shop'>
          </form>
         </div>
         <div class="inline-block">
          <form action="{{route('wish_delete')}}" method='POST'>
           @csrf
           @method('DELETE')
           <input type="hidden" name='productId' value="{{$p->id}}">
           <input type="submit" class='btn btn-danger' value='Remove'>
          </form>
         </div> 
        </div>
       </div>
      @endforeach
     </div>
    @else
     <p class="text-center">You have no product in your wish list</p>
    @endif
   </div>
  </div>
 </div>
 @include('partials.personalized-recommendations-slider')

@endsection

@section('js')
 <script>
  var product_details_modal=null;
  function view_product_details(url){
    if(!product_details_modal){
        createComponent('modal',{},function(modal){
            product_details_modal=modal;
            view_product_details(url);
        });
    }else{
        product_details_modal.open(url);
    }
  }
 </script>
@endsection