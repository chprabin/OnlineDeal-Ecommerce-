@if($products->count())
 <div class="row">
  @foreach($products as $p)
   <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-6">
    <div class="list-item">
     <a href="{{route('product_details',['id'=>$p->id, 'name'=>$p->name])}}" class='block normal-text'>
        @php
         $image=$p->firstImage()->first();
         $image=$image?$image->image:null;
         $best_promo=$p->bestPromo()->first();
         $discounted_price=$p->calculateTotalDiscountedPrice();
        @endphp 
        <div class="image"><img src="{{asset($image)}}" alt="" width='150' height='150'></div>
        @if($best_promo)
         <div>
          <span class='counter' style='color:#363636; font-size:15px;color:brown;font-weight:600' data-date="{{$best_promo->exdate}}"></span>
         </div>
        @endif
        <div class="title"><h3 class="no-bold">{{$p->name}}</h3></div>
        <div class="brand">By <span>{{$p->brand->name}}</span></div>
        <div class="price">
         @if($discounted_price > 0)
         <div class="inline-block">
          <del>
           <sub>$</sub><span style='font-size:21px;'>{{money_whole($p->price)}}</span><sub>{{money_fractional($p->price)}}</sub>
          </del> 
         </div>
         <div class="inline-block" style='margin-left:10px;'>
          <sup>$</sup><span style='font-size:21px;'>{{money_whole($discounted_price)}}</span><sup>{{money_fractional($discounted_price)}}</sup>
         </div>
         @else
         <div class="inline-block">
          <sub>$</sub><span style='font-size:21px;'>{{money_whole($p->price)}}</span><sub>{{money_fractional($p->price)}}</sub>
         </div>
         @endif 
        </div>
     </a>
     @if($p->total_reviews > 0)
      <div class="rating">
       @php
        $average_rating=calculate_average_rating($p->total_ratings, $p->total_reviews);
       @endphp
       <div class="inline-block">@include('partials.rating-stars',['average_rating'=>$average_rating])</div>
       <div class="inline-block"><a href="{{route('product_reviews',
       ['productId'=>$p->id, 'productName'=>$p->name])}}" class='left a-normal'>{{$p->total_reviews}}</a></div>
      </div>
     @endif
    </div>
   </div>
  @endforeach
 </div>
 @php
  $links=$products->appends(reqParams())->links();
 @endphp
 @if(!empty($links))
  <div class="products-pagination" style='margin:20px 0;'>  
    {{$links}}    
  </div>
 @endif
@endif
<script>
 adjustHeights('.list-item div.title');
 init_counters();
 function init_counters(){
  var counters=document.querySelectorAll('.counter');
  counters.forEach(function(c){
   var t1=new Date(c.dataset.date).getTime();
   setInterval(function(){
    var t2=new Date().getTime();
    var timerString=getTimerString(Math.abs(t1-t2));
    c.innerHTML=timerString;
   },1000)
  });
 }
</script>