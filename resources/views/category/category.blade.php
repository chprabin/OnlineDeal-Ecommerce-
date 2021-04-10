@extends('layouts.default')
@section('main')
 <div style='padding:10px 0;'>
  <div class="category">
   <div class="row">
     <!-- sidebar  -->
     <div class="col col-lg-2 col-md-3 col-xs-12 col-sm-12">
        <div class="sidebar" style='border-right:1px solid #ccc;'>
         <!-- sidebar section -->
          <div class="sidebar-section categories-section">
             <h2 class="section-title no-bold">Results for</h2>
             <div class="section-content">
              @include('category.category-hierarchy',['category'=>$category, 'categories'=>$categories])
             </div>
          </div>
         <!-- end of sidebar section -->
         <!-- sidebar section -->
          <div class="sidebar-section filter-section">
           <h2 class="section-title no-bold">Refine by</h2>
           <div class="section-content">
            @php
             $products_search_url=urldecode(route('products_search',reqParams()));
             $products_search_url=buildUrlWithParam($products_search_url, 'c', $category->showname);
            @endphp
            <!-- general filters -->
             <div class="inner-section rating-section">
              <h4 class="inner-section-title filter-title little-margin">Rating</h4>
              <ul class="inner">
               <a href="{{buildUrlRemoveParam($products_search_url, 'rating_star')}}" 
               class='filter-clear block normal-text'>clear</a>
               @php
                $ratings=[5,4,3,2,1];
               @endphp
               @foreach($ratings as $rating)
                @php
                 $hasParam=false;
                 $url=null;
                 if(urlHasParam($products_search_url, 'rating_star',$rating)){
                  $hasParam=true;
                  $url=buildUrlRemoveParam($products_search_url, 'rating_star', $rating);
                 }else{
                  $url=buildUrlWithParam($products_search_url,'rating_star',$rating, true);
                 }
                @endphp
                <li><a href="{{$url}}" class='block cf'>
                 <span class="filter-checkbox {{$hasParam?'fa fa-check checked':null}}" style='margin-top:4px;'></span>
                 <style>
                  .inline-block{
                   display:inline-block; 
                  }
                 </style>
                 @include('partials.rating-stars',['average_rating'=>$rating]) 
                </a></li>
               @endforeach
              </ul>
             </div>
             <!-- end of inner section -->    
             <div class="inner-section">
              <h4 class="inner-section-title filter-title little-margin">Availability</h4>
              <ul class="inner">
               <a href="{{buildUrlRemoveParam($products_search_url, 'availability')}}" class="filter-clear normal-text block">clear</a>
               @foreach(['in_stock'=>'In stock', 'out_of_stock'=>'Out of stock'] as $key=>$text)
                @php
                 $hasParam=false;
                 $url=null;
                 if(urlHasParam($products_search_url, 'availability',$key))
                 {   
                  $url=buildUrlRemoveParam($products_search_url, 'availability',$key);
                  $hasParam=true;
                 }else{
                   $url=buildUrlWithParam($products_search_url, 'availability',$key, true); 
                 }  
                @endphp
                <li><a href="{{$url}}" class="normal-text block cf">
                 <span class="filter-checkbox {{$hasParam?'checked fa fa-check':null}}"></span>
                 <span class="filter-text">{{$text}}</span>
                </a></li>
               @endforeach
              </ul>
             </div>    

             <div class="inner-section">  
              <h4 class="inner-section-title filter-title little-margin">Price</h4>
              <ul class="inner">
               <form action="{{route('products_search')}}" class='price-range' method='GET'>
                @foreach(reqParams(['minprice','maxprice']) as $name=>$value)
                 <input type="hidden" name='{{$name}}' value="{{$value}}">
                @endforeach 
                <input type="hidden" name='c' value='{{$category->showname}}'>
                <input type="text" name='minprice' placeholder='min price' class='form-control control-block' value="{{input('minprice')}}"/>
                <input type="text" name='maxprice' placeholder='max price' class='form-control control-block' value="{{input('maxprice')}}"/>
                <input type="submit" value='Apply price' class='btn btn-block'> 
               </form>  
              </ul>   
             </div>
            <!-- end of general filters -->
            <!-- category specific filters -->
            @foreach($category->filters as $filter)
             @if($filter->options->count())
              <div class="inner-section">
                <h4 class="inner-section-title little-margin filter-title">{{$filter->display_text}}</h4>
                <ul class="inner">
                  <a href="{{buildUrlRemoveParam($products_search_url, $filter->url_slug)}}" class="filter-clear normal-text block cf"></a>
                  @foreach($filter->options as $option)
                   @php
                    $url=null;
                    $hasParam=false;
                    $filter_type=$filter->type;
                    if(urlHasParam($products_search_url, $filter->url_slug, $option->value)){
                      $hasParam=true;
                      $url=buildUrlRemoveParam($products_search_url, $filter->url_slug, $option->value);
                    }else{
                      $url=buildUrlWithParam($products_search_url, $filter->url_slug, $option->value, true);
                    }
                   @endphp
                   <li>
                     <a href="{{$url}}" class="block cf normal-text">
                       <span class="filter-checkbox {{$hasParam?'checkec fa fa-check':null}}"></span>
                       @if($filter_type=='text')
                        <span class="filter-text">{{$option->display_text}}</span>
                       @elseif($filter_type=='color')
                        <span class="filter-option-color" style="background:{{$option->value}}"></span>
                       @endif
                     </a>
                   </li>
                  @endforeach
                </ul>
              </div>
             @endif
            @endforeach
            <!-- end of category specific filters -->     
           </div>
          </div>
         <!-- end of sidebar section -->
        </div> 
     </div>
     <!-- end of sidebar -->  
     <!-- main content -->
     <div class="col col-10">
       <div class="row">
         <h1 class="col col-12 no-margin no-bold" style='margin-bottom:10px;'>{{$category->showname}}</h1>
       </div>
       @php
        $sub_categories=$categories->filter(function($c) use($category){
          return $c->parentId==$category->id;
        });
       @endphp
       @if($sub_categories->count())
        <div class="row">
          <h2 class="col col-12">Shop by category</h2>
          @foreach($sub_categories as $c)
           <div class="col col-lg-3 col-md-3 col-sm-6 col-xs-12">
             <div class="sub-category">
              <a href="{{route('category_by_showname',['showname'=>$c->showname])}}" class="normal-text block">
                <p class="image"><img src="{{asset($c->image)}}" alt="" style='width:200px; height:200px;'></p>
                <div><h3 class="normal-bold" style='font-size:13px; padding-left:5px;'>{{$c->showname}}</h3></div>
              </a> 
             </div>
           </div>
          @endforeach
        </div>
       @endif
       <!-- best sellers -->
        @if($best_sellers->count())
         <div class="category-top-list best-sellers">
          <div class="row">
           <div class="col col-sm-6 col-xs-12"><h2 class="normal-bold no-margin">Best sellers</h2></div>
           <div class="col"><a href="{{route('products_search',['c'=>$category->showname, 'sort'=>'sold_count_desc'])}}" class='normal-text' style='display:block; margin-top:7px;'>More <i style='font-size:10px;' class="fa fa-angle-right"></i></a></div>
          </div>
          <div class="row">
           @foreach($best_sellers as $p)
            <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-6">
             <div class="top-list-item">
              <a href="{{route('product_details',['id'=>$p->id, 'name'=>$p->name])}}" class='block normal-text'>
               @php
                $image=$p->firstImage()->first();
                $image=$image?$image->image:null;
               @endphp
               <p class="image"><img src="{{asset($image)}}" alt="" style='width:200px; height:200px; '></p>
               <p><sup>$</sup><span style='font-size:21px;'>{{money_whole($p->price)}}</span><sup>{{money_fractional($p->price)}}</sup></p>
               <p><h3 class="no-bold" styl='font-size:15px;'>{{shorten_text($p->name)}}</h3></p>
               @if($p->total_reviews)
                @php
                 $average_rating=calculate_average_rating($p->total_ratings, $p->total_reviews);
                @endphp
                <p>
                 <div class="inline-block">@include('partials.rating-stars',['average_rating'=>$average_rating])</div>
                 <div class="inline-block"><span class="left">{{$p->total_reviews}}</span></div>
                </p>
               @endif
              </a>
             </div>
            </div>
           @endforeach
          </div>
         </div>
        @endif
       <!-- end of best sellers -->
       <!-- top ratings -->
       @if($top_ratings->count())
         <div class="category-top-list top-ratings">
          <div class="row">
           <div class="col col-sm-6 col-xs-12"><h2 class="normal-bold no-margin">Top ratings</h2></div>
           <div class="col"><a href="{{route('products_search',['c'=>$category->showname, 'sort'=>'most_popular'])}}" class='normal-text' style='display:block; margin-top:7px;'>More <i style='font-size:10px;' class="fa fa-angle-right"></i></a></div>
          </div>
          <div class="row">
           @foreach($top_ratings as $p)
            <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-6">
             <div class="top-list-item">
              <a href="{{route('product_details',['id'=>$p->id, 'name'=>$p->name])}}" class='block normal-text'>
               @php
                $image=$p->firstImage()->first();
                $image=$image?$image->image:null;
               @endphp
               <p class="image"><img src="{{asset($image)}}" alt="" style='width:200px; height:200px; '></p>
               <p><sup>$</sup><span style='font-size:21px;'>{{money_whole($p->price)}}</span><sup>{{money_fractional($p->price)}}</sup></p>
               <p><h3 class="no-bold" styl='font-size:15px;'>{{shorten_text($p->name)}}</h3></p>
               @if($p->total_reviews)
                @php
                 $average_rating=calculate_average_rating($p->total_ratings, $p->total_reviews);
                @endphp
                <p>
                 <div class="inline-block">@include('partials.rating-stars',['average_rating'=>$average_rating])</div>
                 <div class="inline-block"><span class="left">{{$p->total_reviews}}</span></div>
                </p>
               @endif
              </a>
             </div>
            </div>
           @endforeach
          </div>
         </div>
        @endif
       <!-- end of top ratings -->
       <!--most wished for -->
       @if($most_wished_for->count())
         <div class="category-top-list most-wished-form">
          <div class="row">
           <div class="col col-sm-6 col-xs-12"><h2 class="normal-bold no-margin">Most wished for</h2></div>
           <div class="col"><a href="{{route('products_search',['c'=>$category->showname, 'sort'=>'most_wished'])}}" class='normal-text' style='display:block; margin-top:7px;'>More <i style='font-size:10px;' class="fa fa-angle-right"></i></a></div>
          </div>
          <div class="row">
           @foreach($most_wished_for as $p)
            <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-6">
             <div class="top-list-item">
              <a href="{{route('product_details',['id'=>$p->id, 'name'=>$p->name])}}" class='block normal-text'>
               @php
                $image=$p->firstImage()->first();
                $image=$image?$image->image:null;
               @endphp
               <p class="image"><img src="{{asset($image)}}" alt="" style='width:200px; height:200px; '></p>
               <p><sup>$</sup><span style='font-size:21px;'>{{money_whole($p->price)}}</span><sup>{{money_fractional($p->price)}}</sup></p>
               <p><h3 class="no-bold" styl='font-size:15px;'>{{shorten_text($p->name)}}</h3></p>
               @if($p->total_reviews)
                @php
                 $average_rating=calculate_average_rating($p->total_ratings, $p->total_reviews);
                @endphp
                <p>
                 <div class="inline-block">@include('partials.rating-stars',['average_rating'=>$average_rating])</div>
                 <div class="inline-block"><span class="left">{{$p->total_reviews}}</span></div>
                </p>
               @endif
              </a>
             </div>
            </div>
           @endforeach
          </div>
         </div>
        @endif
       <!-- end of most wished for -->
       <!-- products result -->
        <div class="products-result">
         <!-- result ribbon -->
          <div class="result-ribbon" style='position:relative;'>
           <div class="row">
            <div class="col">
             @php
              $start=($category_products->currentPage()-1)*$category_products->perPage()+1;
              $end=($start-1)+$category_products->perPage();
              if($end > $category_products->total()){
                $end=$end-($end-$category_products->total());
              }
              $printable=$start.'-'.$end." of ".$category_products->total()." Results for";
             @endphp
              <span>{{$printable}}</span>
            </div>

            <div class="col">
             <div class="category-blood-line">
              @include('partials.category-blood-line',['category'=>$category, 'categories'=>$categories])
             </div>
            </div>  

            <div class="col col-lg-3 col-md-3 col-offset-lg-1 col-offset-md-1 col-xs-12 col-sm-12">
             <span class="left">Sort by</span>
             <span class="left" style='margin-left:10px;'>
              <div class="dropdown" style='position:absolute;top:-2px;'>
               @php
                $sorting_list=['oldest'=>'oldest','newest'=>'newest','most_popular'=>'most popular',
                'most_expensive'=>'most expensive','cheapest'=>'cheapest','most_wished'=>'most wished'];
                $sort_value=input('sort');
               @endphp
                <a href="#" class="dropdown-toggle btn">
                 <span class="toggle-text">{{!empty($sort_value)&&isset($sorting_list[$sort_value])?$sorting_list[$sort_value]:'oldest'}}</span>
                 <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu">
                 @foreach($sorting_list as $key=>$value)
                   <li><a href="{{buildUrlWithParam($products_search_url, 'sort',$key)}}" class='menu-item'>{{$value}}</a></li>
                 @endforeach                  
                </ul>
              </div>
             </span>
            </div>  
           </div>
          </div>
         <!-- end of result ribbon -->
         <!-- products list -->
          <div class="products-list" style='padding:10px 0;'>
           @include('product.search-list',['products'=>$category_products])
          </div>
         <!-- end of products list -->
        </div>
       <!-- end of products result -->
     </div>
     <!-- end of main content -->
   </div> 
  </div>
 </div>
 @include('partials.personalized-recommendations-slider')
@endsection

@section('js')
 <script>
  dropdowns();
 </script>
@endsection