@extends('layouts.default')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="little-margin">Customer reviews</h1>
  </div>
 </div>

 <div class="row">
  <!-- review summary -->
   <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <div>
     <h2 class="normal-bold no-margin">{{$product->total_reviews}} customer reviews</h2>
     <div>
      <p>{{calculate_average_rating($product->total_ratings, $product->total_reviews)}} out of 5 stars</p>
      <div>
       @include('partials.product-rating-summary',['review_groups'=>$review_groups])
      </div>
     </div>
    </div>
   </div>
  <!-- end of review summary -->

  <!-- reviews top mid -->
   <div class="col col-lg-5 col-md-5 col-xs-12 col-sm-12">
     <div class="cf">
      <div class="left">
       @php
        $image=$product->firstImage()->first();
        $image=$image?$image->image:null;
       @endphp
       <img src="{{asset($image)}}" alt="" style='width:130px; margin-right:10px;'>
      </div>
      <div class="left" style='width:400px;'>
       <a href="{{route('product_details',['id'=>$product->id, 'name'=>$product->name])}}"
        style='font-size:16px;' class="a-normal block">{{shorten_text($product->name, 100)}}</a>
        <div style='margin:10px 0;'>Price: <span style='color:brown;'>${{number_format($product->price,2,'.','')}}</span></div>
      </div>
     </div>
   </div>
  <!-- end of reviews top mid -->
  
   <!-- reviews top right -->
   <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <div style='border:1px solid #dbdada; border-radius:4px; padding:10px 5%;'>
     @include('product.add-to-cart',['product'=>$product])
     <form action="{{route('wish_save')}}" method='POST'>
      @csrf
      <input type="hidden" name='productId' value='{{$product->id}}'>
      <div class="row">
       <input type="submit" class='btn col col-12' value='Add to wish list'>
      </div>
     </form>
    </div>
   </div> 
   <!-- end of reviews top right -->
</div>

<div class="row">
 <div class="col col-12">
   <div class="v-separator"></div>
 </div>
</div> 

<div class="row">
 <!-- reviews list -->
  <div class="col col-lg-8 col-md-8 col-xs-12 col-sm-12">
    <div class="reviews">
      <div class="updatable-view">
        @include('review.product-reviews-list',['product'=>$product, 'data'=>$data])
      </div>
    </div>
  </div>
 <!-- end of reviews list -->  

 <!-- recommendations -->
  <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
   @if($general_recommendations->count())
    <h3 class="normal-bold">You may also like</h3>
    <div class="recommendations">
     @foreach($general_recommendations as $gr)
      <div class="row">
       <div class="col col-lg-5 col-md-5 col-xs-12 col-sm-12">
        <div class="image">
         @php
          $image=$gr->firstImage()->first();
          $image=$image?$image->image:null;
         @endphp
         <img src="{{asset($image)}}" style='width:100px;' alt="">
        </div>
       </div>
       <div class="col col-lg-7 col-md-7 col-xs-12 col-sm-12">
        <div class="title"><a href="{{route('product_details',['id'=>$product->id, 'name'=>$product->name])}}"
         class="normal-text">{{shorten_text($product->name, 50)}}</a></div>
         @if($product->total_reviews)
        <div style='margin:10px 0;'>
         @php
          $averag_rating=calculate_average_rating($product->total_ratings, $product->total_reviews);
         @endphp
         <a href="{{route('product_reviews',['productId'=>$product->id, 'productName'=>$product->name])}}" 
         class="normal-text">
          @include('partials.rating-stars',['average_rating'=>$averag_rating])
         </a>
        </div>
       @endif
       <div class="price" style='margin:10px 0;'>
        <sup>$</sup><span style='font-size:18px;'>{{money_whole($product->price)}}</span><sup>{{money_fractional($product->price)}}</sup>
       </div>
       <div>
        <form action="{{route('cart_save')}}" method='POST'>
         @csrf
         <input type="hidden" name='productId' value="{{$product->id}}">
         <input type="hidden" name='quantity' value='1'/>
         <input type="submit" class='btn btn-shop' value='Add to cart'>
        </form>
       </div>
       </div>
      </div>
     @endforeach
    </div>
   @endif
  </div>
 <!-- end of recommendations -->
</div>
@include('partials.personalized-recommendations-slider')
@endsection
