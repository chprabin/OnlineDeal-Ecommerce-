@php
 $forbidden_params=['wm','ws','wps','inserted_data'];
 $url=buildUrlRemoveParams(get_full_url(), $forbidden_params);
@endphp
<div class="data-filter">
 <div class="filter-section inline-block">
  <div class="section-title">Filter by</div>
  <div class="section-content">

   <div class="inline-block normal-bold">Rating</div>
   <div class="inline-block" style='margin-right:10px;'>
    <div class="dropdown a-dropdown">
     @php
      $toggle_text='All';
      $rating_input=input('rating_star');
      $ratings=['5'=>'5 stars','4'=>'4 stars','3'=>'3 stars','2'=>'2 stars','1'=>'1 star'];
      if(!empty($rating_input)){
        if(array_key_exists($rating_input, $ratings)){
            $toggle_text=$ratings[$rating_input];
        }
      }    
     @endphp
     <a href="#" class="btn dropdown-toggle"><span class="toggle-text">{{$toggle_text}}</span><i class="fa fa-caret-down"></i></a>
     <ul class="dropdown-menu">
      @foreach($ratings as $star=>$text)
       <li><a href="{{buildUrlWithParam($url, 'rating_star',$star)}}" data-text="{{$text}}"
        class="menu-item refresher">{{$text}}</a></li>
      @endforeach
     </ul>
    </div>
   </div> 

   <div class="inline-block normal-bold">Price</div>
   <div class="inline-block" style='margin-right:10px;'>
    <form action="{{getBaseUrl($url)}}" method='GET' class='search-form'>
     @foreach(reqParams(array_merge($forbidden_params, ['minprice','maxprice'])) as $key=>$value)
      <input type="hidden" name='{{$key}}' value="{{$value}}">
     @endforeach
     <input type="text" name='minprice' value="{{input('minprice')}}" class='form-control' placeholder='min price'>    
     <input type="text" name='maxprice' value="{{input('maxprice')}}" class='form-control' placeholder='max price'>    
     <input type="submit" class='btn btn-primary' value='Apply price'>
    </form>
   </div>   

   <div class="inline-block normal-bold">Sort by</div>
   <div class="inline-block" style='margin-right:10px;'>
    <div class="dropdown a-dropdown">
     @php
      $sort_list=['newest'=>'newest', 'oldest'=>'oldest','most_expensive'=>'most expensive','cheapest'=>'cheapest',
      'most_wished'=>'most wished','most_popular'=>'most popular'];
      $toggle_text='All';
      $sort_input=input('sort');
      if(!empty($sort_input) && array_key_exists($sort_input, $sort_list)){
        $toggle_text=$sort_list[$sort_input];
      }
     @endphp
     <a href="#" class="btn dropdown-toggle"><span class="toggle-text">{{$toggle_text}}</span><i class="fa fa-caret-down"></i></a>
     <ul class="dropdown-menu">
      @foreach($sort_list as $key=>$text)
       <li><a href="{{buildUrlWithParam($url, 'sort', $key)}}" data-text='{{$text}}'
        class="menu-item refresher">{{$text}}</a></li>
      @endforeach
     </ul>
    </div>
   </div>

   <div class="inline-block normal-bold">Display</div>
   <div class="inline-block" style='margin-right:10px;'>
    <div class="dropdown a-dropdown">
     @php
      $list=['10','15','20'];
      $toggle_text='10';
      $display_input=input('per_page');
      if(!empty($display_input) && in_array($display_input, $list)){
        $toggle_text=$display_input;
      }
     @endphp
     <a href="#" class="btn dropdown-toggle"><span class="toggle-text">{{$toggle_text}}</span><i class="fa fa-caret-down"></i></a>
     <ul class="dropdown-menu">
      @foreach($list as $per_page)
       <li><a href="{{buildUrlWithParam($url, 'per_page',$per_page)}}"
        class="menu-item refresher" data-text="{{$per_page}}">{{$per_page}}</a></li>
      @endforeach
     </ul>
    </div>
   </div>   
  </div>
 </div>
</div>