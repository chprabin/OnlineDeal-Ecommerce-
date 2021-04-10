@extends('layouts.default')
@section('main')
@php
 $best_promo=$product->bestPromo()->first();
 $discountedPrice=$product->calculateTotalDiscountedPrice();
@endphp
 <div class="product-details">
  <div class="row">
   <!-- top left -->
    @if($product->images->count())
     <div class="col col-lg-4 col-m-4 col-xs-12 col-sm-12">
      <div class="top-left cf">
       @include('product.details-images',['product'=>$product])
      </div>
     </div>
    @endif
   <!-- end of top left -->
   <!-- top mid -->
    <div class="col col-lg-5 col-md-5 col-xs-12 col-sm-12">
     <div class="top-mid">
      <h2 class="product-title normal-bold">{{$product->name}}</h2>
      <p>By <span>{{$product->brand->name}}</span></p>
      <!-- reviews summary -->
     @if($product->total_reviews)
      <div style='margin:10px 0;'>
        @php
         $average_rating=calculate_average_rating($product->total_ratings, $product->total_reviews);
        @endphp
        <div class="inline-block">
          <div class="dropdown">
           <a href="#" class="dropdown-toggle">@include('partials.rating-stars',['average_rating'=>$average_rating]) <i class="fa fa-caret-down" style='color:gray;'></i></a>
           <ul class="dropdown-menu">
            <div style='width:400px; padding:10px 20px;'>
             <p class='text-center'>{{$average_rating}} out of 5 stars</p>
             <div>@include('partials.product-rating-summary',['review_groups'=>$review_groups])</div>
            </div>
           </ul>
          </div>
        </div>
        <div class="inline-block">
         <a href="#reviews" class="a-normal" style='margin-left:10px;'>{{$product->total_reviews}} customer reviews</a>
        </div>
        <div class="inline-block">
         <a href="#similar-products" class="a-normal" style='margin-left:10px;'>compare with similar products</a> 
        </div> 
       </div> 
      @endif
      <!-- end of reviews summary -->
      <div class="v-separator"></div>
      @if($discountedPrice > 0)
       <div style='margin:10px 0;'>
        <div class="inline-block">
         <del><span class='normal-bold' style='color:#363636; font-size:16px;'>${{$product->price}}</span></del>
        </div>
        <div class="inline-block" style='margin-left:15px;'>
         <span style='color:brown;font-size:16px;' class='normal-bold'>${{$discountedPrice}}</span>
        </div>
       </div>
      @else
       <p class="product-price">Price: <span class="normal-bold" style='color:brown;'>${{$product->price}}</span></p>
      @endif
      <div class="product-description"><?php echo $product->desc;?></div>
     </div>
    </div>
   <!-- end of top mid -->
   <!-- top right -->
   <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
     <div class="top-right">
      <div style='margin:10px 0;'>
       <div style='padding:10px 2%; border:1px solid #ccc;'>
        @if($product->stock==0)
         <div><span style='color:brown;font-size:15px;'>Product is out of stock. <br/> we will make it available as soon as possible.</span></div>
        @else
         <div style='margin-bottom:10px;'>
          @if($discountedPrice)
           <div class="inline-block">
            <span style='color:brown; font-size:20px;'>${{$discountedPrice}}</span>
           </div>
           <div class="inline-block" style='margin-left:10px;'>
            <span class="counter" style='color:brown; font-size:19px;' data-date="{{$best_promo->exdate}}"></span>
           </div>
          @else
           <span style='color:brown; font-size:20px;'>${{$product->price}}</span>
          @endif
         </div>
         <div style='margin-bottom:10px;'>
          @include('product.add-to-cart',['product'=>$product])
          <form action="{{route('wish_save')}}" method='POST'>
            @csrf
            <input type="hidden" name='productId' value='{{$product->id}}'>
            <div class="row">
            <input type="submit" class='btn col col-12' value='Add to wish list'>
            </div>
          </form>
         </div>
        @endif 
       </div>
      </div>       
     </div>
   </div>
   <!-- end of top right -->
  </div>
 
  <div class="row">
    <div class="col col-12">
      <div class="v-separtor"></div>
    </div>
  </div>
  <!-- general recommendations -->
  @if($general_recommendations->count())
   <div class="row">
    <div class="col col-12">
     <h2 class="no-bold">User who liked this item also liked</h2>
     <div class="slider-wrapper general-recommender-slider recommender-slider">
       <div class="slider cf">
        @include('partials.product-details-recommendations',['data'=>$general_recommendations])
       </div> 
       <a href="#" class='rotator left-rotator'><span class="fa fa-angle-left"></span></a>
       <a href="#" class='rotator right-rotator'><span class="fa fa-angle-right"></span></a>
     </div>
    </div>
   </div>
  @endif
  <!-- end of general recommendations -->
  <!-- similar products row -->
  @if($similar_products->count())
   <div class="row">
     <div class="col col-12">
      <h3 class="normal-bold" style='color:brown;'>Compare with similar products</h3> 
      <div id="similar-products">
        <!-- top -->
         <div class="top">
          <div class="basic-info">
            <div class="image"><img src="{{asset($product->images[0]->image)}}" alt=""></div>
            <div class="title"><span class="normal-text">{{$product->name}}</span></div>
            <div>
              <form action="{{route('cart_save')}}" method='POST' class='add-to-cart'>
                @csrf
                <input type="hidden" name='productId' value='{{$product->id}}'>
                <input type="hidden" name='quantity' value='1'>
                <input type="submit" class='btn btn-shop btn-block' value='Add to cart'>
              </form>
            </div>
          </div>
          @foreach($similar_products as $p)
           <div class="basic-info">
             <a href="{{route('product_details',['id'=>$p->id, 'name'=>$p->name])}}"
              class="block a-normal">
               <div class="image"><img src="{{asset($p->images[0]->image)}}" alt=""></div>
               <div class="title"><span class="a-normal">{{$p->name}}</span></div>
              </a>
              <div>
               <form action="{{route('cart_save')}}" method='POST' class='add-to-cart'>
                 @csrf
                 <input type="hidden" name='productId' value='{{$p->id}}'>
                 <input type="hidden" name='quantity' value='1'>
                 <input type="submit" class='btn btn-shop btn-block' value='Add to cart'>
               </form>
              </div>
           </div>
          @endforeach
         </div>
        <!-- end of top -->
        <!-- bottom  -->
         <div class="bottom">
          <!-- reviews row -->
           <div class="comparison-row reviews-row">
            <div class="comparison-cell title-cell">Customer rating</div>
            <div class="comparison-cell value-cell">
             @if($product->total_reviews)
                @php 
                 $average_rating=calculate_average_rating($product->total_ratings, $product->total_reviews);
                @endphp
                <div class="inline-block">
                 @include('partials.rating-stars',['average_rating'=>$average_rating])
                </div>
                <div class="inline-block">
                 <a href="{{route('product_reviews',['productId'=>$product->id, 'productName'=>$product->name])}}" class="left a-normal">
                  {{$product->total_reviews}}
                 </a>
                </div>
             @else
              <span class='block text-center'>-</span>   
             @endif
            </div>
            @foreach($similar_products as $p)
             <div class="comparison-cell value-cell">
              @if($p->total_reviews)
                 @php 
                  $average_rating=calculate_average_rating($p->total_ratings, $p->total_reviews);
                 @endphp
                 <div class="inline-block">
                  @include('partials.rating-stars',['average_rating'=>$average_rating])
                 </div>
                 <div class="inline-block">
                  <a href="{{route('product_reviews',['productId'=>$p->id, 'productName'=>$p->name])}}" class="left a-normal">
                   {{$p->total_reviews}}
                  </a>
                 </div>
              @else
               <span class='block text-center'>-</span>   
              @endif
             </div>
            @endforeach
           </div>
          <!-- end of review row -->
          <!-- price row -->
           <div class="comparison-row">
             <div class="comparison-cell title-cell">
              Price               
             </div>
             <div class="comparison-cell value-cell">
               <sup>$</sup><span style='font-size:21px;'>{{money_whole($product->price)}}</span>
               <sup>{{money_fractional($product->price)}}</sup>
             </div>
             @foreach($similar_products as $p)
              <div class="comparison-cell value-cell">
               <sup>$</sup><span style='font-size:21px;'>{{money_whole($p->price)}}</span>
               <sup>{{money_fractional($p->price)}}</sup>
              </div>
             @endforeach
           </div>
          <!-- end of price row -->
          <!-- brand row -->
           <div class="comparison-row">
            <div class="comparison-cell title-cell">Brand</div>
            <div class="comparison-cell value-cell">{{$product->brand->name}}</div>
            @foreach($similar_products as $p)
             <div class="comparison-cell value-cell">{{$p->brand->name}}</div>
            @endforeach             
           </div>
          <!-- end of brand row -->
          <!-- filter rows -->
          @foreach($product->options as $option)
           <div class="comparison-row">
            <div class="comparison-cell title-cell">{{$option->filter->display_text}}</div>
            <div class="comparison-cell value-cell">
              @if($option->filter->type=='text')
               <span class="left">{{$option->display_text}}</span>
              @elseif($option->filter->type=='color')
               <span class="left" style='width:20px; hight:20px; background:{{$option->value}}'></span>
              @endif
            </div>
            @foreach($similar_products as $p)
             @php
              $po=$p->options->first(function($o) use($option){
                return $o->filter->id==$option->filter->id;
              });
             @endphp
             <div class="comparison-cell value-cell">
             @if($po)
               @if($po->filter->type=='text')
                <span class="left">{{$po->display_text}}</span>
               @elseif($po->filter->type=='color')
                <span class="left" style='width:20px; hight:20px; background:{{$po->value}}'></span>
               @endif
             @else
              <span class="left text-center">-</span>
             @endif
             </div>
            @endforeach
           </div>
          @endforeach  
          <!-- end of filter rows -->
         </div>
        <!-- end of bottom -->
      </div>
     </div>
   </div>
  @endif  
  <!-- end of similar products -->
  <!--product information row -->
   @if($product->options->count())
    <div class="row">
      <div class="col col-12">
        <h3 class="normal-bold" style='color:brown;'>Product information</h3>
        <div class="product-info">
          @foreach($product->options as $option)
           <div class="info-row">
             <div class="info-cell title-cell">{{$option->filter->display_text}}</div>
             <div class="info-cell value-cell">
               @if($option->filter->type=='text')
                <span class=''>{{$option->display_text}}</span>
               @elseif($option->filter->type=='color')
                <span class="left" style='width:20px; height:20px; background:{{$option->value}}'></span>
               @endif
             </div>
           </div>
          @endforeach
        </div>
      </div>
    </div>
   @endif
 <!-- end of product  information row -->
 <div class="row">
   <div class="col col-12">
     <div class="v-separator"></div>
   </div>
 </div>

 <!-- product reviews row-->
  <div class="row">
    <!-- reviews left -->
     @if($product->total_reviews)
      <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
        @php
         $average_rating=calculate_average_rating($product->total_ratings, $product->total_reviews);
        @endphp
        <h3 class="bold no-margin">{{$product->total_reviews}} customer reviews</h3>
        <div style='padding:10px 0;'>
         <div class="normal-bold" style='padding:5px 0;'>
          <div class="inline-block">@include('partials.rating-stars',['average_rating'=>$average_rating])</div>
          <div class="inline-block text-center" style='margin-left:10px;'><span class="left">{{$average_rating}} out of 5 stars</span></div>
         </div>
         @include('partials.product-rating-summary',['product'=>$product])
         @if(!Auth::check())
          <p>
            <a href="{{route('login')}}" class="block btn">Login to create a review</a>
          </p>
         @else
          <div class="v-separator"></div>
          <p>
            <h3 class="no-margin bold">Review this product</h3>
            <div style='padding:10px 0;'>
             @include('review.create-form',['product'=>$product])
            </div>
          </p>
         @endif
        </div>
      </div>
     @endif
    <!-- end of reviews left -->          
    <!-- reviews right -->
     <div class="col col-lg-5 col-md-5 col-xs-12 col-sm-12">
       <h3 class="normal-bold">Latest reviews</h3>
       <div class="reviews-list">
        @if($product->reviews->count())
         @foreach($product->reviews as $r)
          @include('review.single-review',['review'=>$r])
         @endforeach
         <p>
           <a href="{{route('product_reviews',['productId'=>$product->id, 'productName'=>$product->name])}}"
              class="a-normal block normal-bold">See all reviews <i class="fa fa-angle-right" style='position:relative;top:0px;font-size:11px;'></i></a>
         </p>
         @if(!Auth::check())
          <a href="{{route('login')}}" class="btn">Login to create a review</a>
         @endif
        @else
         <p class="text-center">no reviews found</p>
        @endif   
       </div>
     </div>
    <!-- end of reviews right -->
  </div>
 <!-- end of product reviews row -->
 </div>
@endsection
@section('js')
 <script>
  dropdowns();
  adjustHeights('#similar-products .basic-info .title');
  var productId="{{$product->id}}";
  require(['request','slider'],function(Request, Slider){
    var req=new Request();
    var slider=new Slider({selector:'.general-recommender-slider'});
    slider.on_data_load(function(slider){
      var res=req.get('/products/'+productId+'/recommendations',{page:slider.current_page+1});
      if(res.result){
        slider.extend(res.view);
        return true;
      }
    });
    slider.init();
  });


  var counter=document.querySelector('.counter');
  if(counter){
   var t1=new Date(counter.dataset.date).getTime();
   setInterval(function(){
    var t2=new Date().getTime();
    var time_diff=Math.abs(t2-t1);
    var string=getTimerString(time_diff);
    counter.innerHTML=string; 
   },1000);
  }
 </script>
@endsection