@extends('layouts.default')
@section('main')
 @php
  $category=isset($category)?$category:null;
  $products_search_url=get_full_url();
 @endphp
 <div class="products">
  <div class="row">
   <!-- sidebar -->
    <div class="col col-lg-2 col-md-3 col-xs-12 col-sm-12">
     <div class="sidebar">
      <div class="sidebar-section categories-section">
       <h2 class="section-title no-bold">Results for</h2>
       <div class="section-content">
        @if($category)
         <ul>
         @if($category->parentCategory)
          <li>
           <a href="{{buildUrlWithParam($products_search_url, 'c', $category->parentCategory->showname)}}"
            class="normal-text no-bold">{{$category->parentCategory->showname}}</a>
          </li>
         @endif
          <li><span class='normal-text normal-bold'>{{$category->showname}}</span>
           @if($searched_categories->count())
            <ul class="inner">
             @foreach($searched_categories as $sc)
              <li><a href="{{buildUrlWithParam($products_search_url,'c',$sc->showname)}}" 
              class="normal-text normal-bold">{{$sc->showname}}</a></li>
             @endforeach
            </ul>
           @endif
          </li>
        @elseif($searched_categories->count())
         <ul>
          @foreach($searched_categories as $sc)
           <li><a href="{{buildUrlWithParam($products_search_url, 'c', $sc->showname)}}"
            class='normal-text'>{{$sc->showname}}</a></li>
          @endforeach 
         </ul>  
        @endif
       </div>
      </div>

      <!-- filter section -->
       <div class="filter-section sidebar-section">
        <h2 class="section-title no-bold">Refine by</h2>
        <div class="section-content">
          <!-- general filters -->
           <div class="inner-section rating-section">
            <h4 class="filter-title inner-section-title little-margin">Rating</h4>
            <ul class="inner">
             <a href="{{buildUrlRemoveParam($products_search_url, 'rating_star')}}" 
             class="filter-clear normal-text cf block">Clear</a>
             @php
              $ratings=[5,4,3,2,1];
             @endphp
             @foreach($ratings as $rating)
              @php
               $hasParam=false;
               $url=null;
               if(urlHasParam($products_search_url, 'rating_star',$rating)){
                $url=buildUrlRemoveParam($products_search_url, 'rating_star',$rating);
                $hasParam=true;
               }else{
                $url=buildUrlWithParam($products_search_url, 'rating_star', $rating, true); 
               }
              @endphp
              <li><a href="{{$url}}" class='block cf'>
               <span class="filter-checkbox {{$hasParam?'fa fa-check checked':null}}" style='margin-top:4px;'></span>
               @include('partials.rating-stars',['average_rating'=>$rating])
              </a></li>
             @endforeach
            </ul>
           </div>

           <div class="inner-section">
             <h4 class="inner-section-title filter-title little-margin">Availability</h4>
             <ul class="inner">
              <a href="{{buildUrlRemoveParam($products_search_url, 'availability')}}" 
              class="filter-clear normal-text cf block">Clear</a>
              @foreach(['in_stock'=>'In stock','out_of_stock'=>'Out of stoc'] as $key=>$text)
               @php
                $url=null;
                $hasParam=false;
                if(urlHasParam($products_search_url, 'availability', $key)){
                  $url=buildUrlRemoveParam($products_search_url, 'availability', $key);
                  $hasParam=true;
                }else{
                  $url=buildUrlWithParam($products_search_url, 'availability', $key, true);
                }
               @endphp
               <li>
                <a href="{{$url}}" class='block cf normal-text'>
                 <span class="filter-checkbox {{$hasParam?'checked fa fa-check':null}}"></span>
                 <span class="filter-text">{{$text}}</span>
                </a>
               </li>
              @endforeach
             </ul>  
           </div>

           <div class="inner-section">
            <h4 class="inner-section-title filter-title little-margin">Price</h4>
            <ul class="inner">
             <form action="{{route('products_search')}}" class="price-range" method='GET'>
              @foreach(reqParams(['minprice','maxprice']) as $name=>$value)
               <input type="hidden" name='{{$name}}' value="{{$value}}">
              @endforeach
              <input type="text" name='minprice' value="{{input('minprice')}}" class='form-control control-block' placeholder='min price'>
              <input type="text" name='maxprice' value="{{input('maxprice')}}" class='form-control control-block' placeholder='max price'>
              <input type="submit" value='Apply price' class='btn btn-block'>
             </form>
            </ul>
           </div>      
          <!-- end of general filters -->
          <!-- specific filters -->
          @if($category)
           @foreach($category->filters as $filter)
            @if($filter->options->count())
             <div class="inner-section">
              <h4 class="inner-section-title little-margin filter-title">{{$filter->display_text}}</h4>
              <ul class="inner">
               <a href="{{buildUrlRemoveParam($products_search_url, $filter->url_slug)}}" 
               class="filter-clear block normal-text cf">Clear</a>
               @foreach($filter->options as $option)
                @php
                 $url=null;
                 $hasParam=false;
                 if(urlHasParam($products_search_url, $filter->url_slug, $option->value)){
                  $hasParam=true;
                  $url=buildUrlRemoveParam($products_search_url,$filter->url_slug,$option->value);
                 }else{
                  $url=buildUrlWithParam($products_search_url, $filter->url_slug, $option->value, true);
                 }
                @endphp
                <li>
                 <a href="{{$url}}" class="block cf normal-text">
                  <span class="filter-checkbox {{$hasParam?'fa fa-check checked':null}}"></span>
                  @if($filter->type=='text')
                   <span class="filter-text">{{$option->display_text}}</span>
                  @elseif($filter->type=='color')
                   <span class="filter-option-color" style="background:{{$option->value}}"></span>
                  @endif
                 </a>
                </li>
               @endforeach
              </ul>
             </div>   
            @endif
           @endforeach
          @endif 
          <!-- end of specific filters -->
        </div>
       </div>
      <!-- end of filter section -->
     </div>
    </div>
   <!--end of sidebar -->
   <!-- products result -->
    <div class="col col-lg-10 col-md-9 col-xs-12 col-sm-12">
     <div class="products-result">
     @if($products->count())
      <!-- result ribbon -->
       <div class="result-ribbon">
        <div class="row">
         <div class="col">
          @php 
           $start=($products->currentPage()-1)*$products->perPage()+1;
           $end=($start-1)+$products->perPage();
           if($end > $products->total()){
            $end=$end-($end-$products->total());
           }
           $printable=$start.'-'.$end.' of '.$products->total().' Results for';
          @endphp
          <span>{{$printable}}</span>
         </div>

         <div class="col">
          @if($category)
           <div class="category-blood-line">
            @include('partials.category-blood-line',['category'=>$category, 'categories'=>$categories])
           </div>
          @endif
         </div>  

         @if(!empty(input('sq')))
          <div class="col">
           <span>{{input('sq')}}</span>
          </div>
         @endif
         <div class="col col-offset-lg-1 col-offset-md-1 col-lg-3 col-md-3 col-xs-12 col-sm-12">
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
       <div class="products-list">
        @include('product.search-list',['products'=>$products])
       </div>
      <!-- end of products list -->
     @else
      <div>No data found</div>
     @endif
     </div>
    </div>
   <!-- end of  products result -->
  </div>
 </div>
 @include('partials.personalized-recommendations-slider')

@endsection
@section('js')
 <script>
  dropdowns(); 
 </script>
@endsection
