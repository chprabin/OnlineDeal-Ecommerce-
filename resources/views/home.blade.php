@extends('layouts.default')
@section('css')
 <style>
  .banners{
      background:linear-gradient(to bottom, white, #dddbdb);
      min-height:300px;
      padding-top:20px;
  }
  .banners-slider-wrapper .rotator{
      position:absolute;
      top:36%;
      width:50px;
      height:50px;
      border-radius:100%;
      background:#bebebe;
      text-align:center;
      line-height:50px;
      font-size:25px;
      color:#ededed;
  }
  .banners-slider-wrapper .rotator span{
      line-height:50px;
  }
  .banners-slider-wrapper .left-rotator{
      left:4%;
  }
  .banners-slider-wrapper .right-rotator{
      right:4%;
  }
  .category-grid{
      position:relative;
      top:-42px;
  }
  .category-grid-item{
      box-shadow:0 0 3px #bcbcbc;
      background:white;
      padding:1% 4%;
  }
  .category-grid-item a{
      text-decoration:none;
      color:#363636;
  }
  .product-group{
    box-shadow: 0 0 3px
    silver;
    padding: 10px 2%;
    margin:30px 0;
  }

  .product-group .rotators{
      position:absolute;
      top:1px;
      right:0;
      width:200px;
      text-align:center;
  }
  .product-group .rotator{
      position:relative;
      background:transparent;
      font-size:48px;
      color:#363636;
  }
  .product-group .rotator.left-rotator{
      margin-right:21px;
  }
  .product-group .slider-title{
      color:#363636;
  }
  
 </style>
@endsection
@section('main')
 <!-- banners -->
 @if($banners->count())
  <div class="banners">
   @include('partials.home-banners-slider',['data'=>$banners])
  </div>
 @endif
 <!-- end of  banners -->
 <!-- category grid -->
 @if($category_grid->count())
    <div class="category-grid">
     <div style='padding:0 2%;'>
      @include('partials.home-category-grid',['data'=>$category_grid])
     </div>
    </div>
 @endif
 <!-- end of category grid -->
 <!-- product groups -->
 <div class="row">
  <div class="col col-12">
   <div class="product-groups">
    <div class="product-group">
     @include('partials.home-product-slider',['data'=>$best_sellers, 'slider_wrapper_class'=>
     'best-sellers-slider-wrapper','remote_url'=>'/best-sellers','slider_group_partial'=>
     'partials.home-best-sellers-slider-group','slider_title'=>'Best sellers'])
    </div>
    <div class="product-group">
     @include('partials.home-product-slider',['data'=>$top_ratings, 'slider_wrapper_class'=>
     'top-ratings-slider-wrapper','remote_url'=>'/top-ratings','slider_group_partial'=>
     'partials.home-top-ratings-slider-group','slider_title'=>'Top ratings'])
    </div>
    <div class="product-group">
     @include('partials.home-product-slider',['data'=>$most_wished, 'slider_wrapper_class'=>
     'most-wished-slider-wrapper','remote_url'=>'/most-wished','slider_group_partial'=>
     'partials.home-most-wished-slider-group','slider_title'=>'Most wished for'])
    </div>
   </div>
  </div>
 </div>
 <!-- end of product groups -->
 @include('partials.personalized-recommendations-slider')
@endsection

@section('js')
 <script>
  createComponent('slider',{selector:'.banners-slider-wrapper'},function(slider){
    slider.init();
  });
 </script>
@endsection