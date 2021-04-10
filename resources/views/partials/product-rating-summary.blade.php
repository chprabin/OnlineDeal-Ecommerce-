<style>
 .rating-progress-bg{
     width:200px;
     background:linear-gradient(to bottom, #f0ecec, #ddd);
     border-radius:4px;
     border:1px inset #f5f5f5;
 }

 .rating-progress{
     background:orange;
     height:20px;
 }
</style>
@php
 $total_reviews=0;
 foreach($review_groups as $rg){
    $total_reviews+=$rg->total_reviews;
 }
@endphp
<div class="product-rating-summary">
 @foreach($review_groups as $rg)
  @php
   $reviews_percentage=round(($rg->total_reviews/$total_reviews)*100);
  @endphp
  <a href="{{route('product_reviews',['productId'=>$product->id, 'productName'=>$product->name, 'rating_star'=>$rg->star])}}"
   class="block a-normal">
    <div class="summary-line cf" style='margin-bottom:10px;'>
     <div class="left" style='margin-right:7px;'>{{$rg->star}} star</div>
     <div class="left">
      <div class="rating-progress-bg">
       <div class="rating-progress" style="width:{{$reviews_percentage.'%'}}"></div>
      </div>
     </div>
     <div class="left" style='margin-left:5px;'>
      <span style='color:gray;'>{{$reviews_percentage}} %</span>
     </div>
    </div>
   </a>
 @endforeach
</div>