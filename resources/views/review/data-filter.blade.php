@php
 $url=get_full_url();
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

   <div class="inline-block normal-bold">Sort by</div>  
   <div class="inline-block">
    <div class="dropdown a-dropdown">
     @php
      $list=['top_rated'=>'top rated','newest'=>'newest'];
      $toggle_text='oldest';
      $sort_input=input('sort');
      if(!empty($sort_input) && array_key_exists($sort_input, $list)){
       $toggle_text=$list[$sort_input];
      }
     @endphp
     <a href="#" class="btn dropdown-toggle"><span class="toggle-text">{{$toggle_text}}</span><i class="fa fa-caret-down"></i></a>
     <ul class="dropdown-menu">
      @foreach($list as $key=>$text)
       <li><a href="{{buildUrlWithParam($url, 'sort', $key)}}"
        class="menu-item refresher" data-text="{{$text}}">{{$text}}</a></li>
      @endforeach
     </ul>
    </div>
   </div>   
   </div> 
 </div>
</div>