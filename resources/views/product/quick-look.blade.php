@php
 $discounted_price=$product->calculateTotalDiscountedPrice();
@endphp
<div class="product-details">
 <div class="row">
  <!-- images -->
   @if($product->images->count())
    <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
     <div>
      @include('product.details-images',['product'=>$product, 'main_image_size'=>200])
     </div>
    </div>
   @endif
  <!-- end of images -->
  <!-- title and description -->
   <div class="col col-lg-5 col-md-5 col-xs-12 col-sm-12">
    <div>
     <h2>{{$product->name}}</h2>
     <div style='margin:10px;'>
      @if($discounted_price > 0)
       <div class="inline-block" style='margin-right:10px;'>
        <del>
         <span style='font-size:20px; color:#363636;'>${{number_format($product->price,2,'.','')}}</span>
        </del>
       </div>
       <div class="inline-block">
        <span style='font-size:20px; color:brown;'>${{number_format($discounted_price,2,'.','')}}</span>
       </div>
      @else
       <div class="inline-block">
       <span style='font-size:20px; color:#363636;'>${{number_format($product->price,2,'.','')}}</span>
       </div>
      @endif
     </div>
     <!-- <p>
      <span style='font-size:20px; color:brown;'>${{number_format($product->price,2,'.','')}}</span>
     </p> -->
     @if($product->total_reviews)
      @php
       $average_rating=calculate_average_rating($product->total_ratings,$product->total_reviews);
      @endphp
      <div style='margin:10px 0;'>
       <div class="dropdown">
        <a href="#" class="normal-text dropdown-toggle">
         @include('partials.rating-stars',['average_rating'=>$average_rating]) <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu">
         <div style='padding:10px 2%; width:300px;'>
          @include('partials.product-rating-summary',['product'=>$product])
         </div>
        </ul>
       </div>
      </div>
     @endif
     <div style='margin:10px 0;' class='product-description'>
      <?php
       echo $product->desc;
      ?>
     </div>
    </div>
   </div>
  <!-- end of title and description -->
  <!-- shop -->
   <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <div style='padding:10px 2%;border:1px solid #ccc;'>
     <div style='margin-bottom:20px;'>
      @if($discounted_price > 0)
       <span>${{number_format($discounted_price,2,'.','')}}</span>
      @else
       <span>${{number_format($product->price,2,'.','')}}</span>
      @endif
     </div>
     <div style='margin-bottom:10px;'>
      @include('product.add-to-cart',['product'=>$product])
     </div>   
    </div>
   </div>
  <!-- end of shop -->
 </div>
</div>

<script>
 dropdowns();
 createComponent('ajaxform',{selector:'.add-to-cart'},function(af){
    af.onBeforeSend(function(){
        addAjaxLoader();
        return true;
    });
    af.onSend(function(f,r){
     removeAjaxLoader();
    });
    af.init();
 });
</script>