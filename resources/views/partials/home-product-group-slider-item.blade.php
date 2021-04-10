<div class="item">
     @php
      $image=$d->firstImage()->first();
      $image=$image?$image->image:null;
     @endphp
     <div class="image"><img src="{{asset($image)}}" style='with:150px; height:150px;' alt=""></div>
     <div class="title" style='margin:10px 0;'>
      <a href="{{route('product_details',['id'=>$d->id, 'name'=>$d->name])}}"
       class="normal-text">{{shorten_text($d->name, 50)}}</a>
     </div>
     @if($d->total_reviews)
      @php
       $average_rating=calculate_average_rating($d->total_ratings, $d->total_reviews);
      @endphp
      <div style='margin:10px 0;'>
       <a href="{{route('product_reviews',['productId'=>$d->id,'productName'=>$d->name])}}">
        @include('partials.rating-stars',['averag_rating'=>$average_rating])
       </a>
      </div>
     @endif
     <div>
      <sup>$</sup><span style='font-size:20px; color:#363636;'>{{money_whole($d->price)}}</span>
      <sup>{{money_fractional($d->price)}}</sup>
     </div>
     <div style='margin-top:10px;'>
      <form action="{{route('cart_save')}}" method='POST'>
      @csrf
        <input type="hidden" name='productId' value='{{$d->id}}'>
        <input type="hidden" name='quantity' value='1'>
        <input type="submit" class='btn btn-shop' value='Add to cart'>
      </form>
    </div>  
    </div>
    