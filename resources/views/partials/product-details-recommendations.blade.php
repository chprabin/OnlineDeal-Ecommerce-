@if($data->count())
 <div class="group">
  <div class="row">
   <div class="col col-10 col-offset-1">
    <div class="row">
     @foreach($data as $d)
      <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
       <div class="item">
        <div class="image">
         @php
          $image=$d->firstImage()->first();
          $image=$image?$image->image:null;
         @endphp
         <img src="{{asset($image)}}" class='product-image' alt="">
        </div>
        <div class="title">
         <a href="{{route('product_details',['id'=>$d->id, 'name'=>$d->name])}}"
          class="a-normal">{{$d->name}}</a>
        </div>
        @if($d->total_reviews)
         <div>
          @php
           $average_rating=calculate_average_rating($d->total_ratings, $d->total_reviews);
          @endphp
          <a href="{{route('product_reviews',['productId'=>$d->id, 'productName'=>$d->name])}}">
           @include('partials.rating-stars',['average_rating'=>$average_rating])
          </a>
         </div>
        @endif
        <div style='margin:10px 0;'>
         <sup>$</sup><span style='font-size:20px;'>{{money_whole($d->price)}}</span><sup>{{money_fractional($d->price)}}</sup>
        </div>
        <div>
         <form action="{{route('cart_save')}}" method='POST'>
          @csrf
          <input type="hidden" name='productId' value="{{$d->id}}">
          <input type="hidden" naeme='quantity' value="1">
          <input type="submit" class='btn bnt-shop' value='Add to cart'>
         </form>
        </div>
       </div>
      </div>
     @endforeach
    </div>
   </div>
  </div>
 </div>
@endif
<script>
 adjustHeights('.recommender-slider .item div.title');
</script>