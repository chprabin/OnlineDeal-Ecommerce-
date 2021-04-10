<div class="group">
 <div class="row">
  @foreach($data as $d)
   <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <div class="item">
     <div class="image">
      <img src="{{asset($d->firstImage()->first()->image)}}" style='width:150px;height:150px;' alt="">
     </div>
     <div class="title" style='margin:10px 0;'>
      <a href="{{route('product_details',['id'=>$d->id, 'name'=>$d->name])}}" 
      class="normal-text">{{shorten_text($d->name, 100)}}</a>
     </div>
     @if($d->total_reviews)
      @php
       $average_rating=calculate_average_rating($d->total_ratings, $d->total_reviews);
      @endphp
      <div>
       <a href="{{route('product_reviews',['productId'=>$d->id, 'productName'=>$d->name])}}">
        @include('partials.rating-stars',['average_rating'=>$average_rating])
       </a>
      </div>
     @endif
     <div class='price' style='margin:10px 0;'>
      <sup>$</sup><span style='font-size:20px;'>{{money_whole($d->price,2,'.','')}}</span><sup>{{money_fractional($d->price)}}</sup>
     </div>
     <div>
      <form action="{{route('cart_save')}}" method='POST'>
       @csrf
       <input name="productId"  type='hidden' value='{{$d->id}}'>
       <input name="quantity"  type='hidden' value='1'>
       <input type="submit" class='btn btn-shop' value='Add to cart'>
      </form>
     </div>
    </div>
   </div>
  @endforeach
 </div>
</div>

<script>
 adjustHeight('.prsw div.title');
</script>