@php
 $forbidden_params=['wm','ws','wps','inserted_data'];
 $url=buildUrlRemoveParams(get_full_url(), $forbidden_params);
@endphp    
<div class="data-filter">
 
    <div class="filter-section inline-block">
      <div class="section-title">Filter by</div>
      <div class="section-content">
        <div class="inline-block normal-bold">Order state</div>
        <div class="inline-block" style='margin-right:10px;'>
         <div class="dropdown a-dropdown">
          @php
           $state_names=$states->pluck('name')->toArray();
           $toggle_text='All';
           $input_state=input('state');
           if(!empty($input_state) && in_array($input_state, $state_names)){
            $toggle_text=$input_state;
           }
          @endphp
          <a href="#" class="btn dropdown-toggle"><span class="toggle-text">{{$toggle_text}}</span> <i class="fa fa-caret-down"></i></a>
          <ul class="dropdown-menu">
           <li><a href="{{buildUrlRemoveParam($url, 'state')}}" class='refresher menu-item' data-text='All'>All</a></li>
           @foreach($state_names as $state)
            <li><a href="{{buildUrlWithParam($url, 'state', $state)}}" data-text='{{$state}}' 
            class="menu-item refresher">{{$state}}</a></li>
           @endforeach
          </ul>
         </div> 
        </div>  

        <div class="inline-block normal-bold">Order total</div>
        <div class="inline-block" style='margin-right:10px;'>
         <form action="{{getBaseUrl($url)}}" method='GET' class='search-form'>
          @foreach(reqParams(array_merge($forbidden_params, ['mintotal','maxtotal'])) as $name=>$value)
           <input type="hidden" name="{{$name}}" value="{{$value}}">
          @endforeach
          <input type="text" name='mintotal' value="{{input('mintotal')}}" placeholder='enter min total' class='form-control'>
          <input type="text" name='maxtotal' value="{{input('maxtotal')}}" placeholder='enter max total' class='form-control'>
          <input type="submit" class='btn btn-primary' value='Apply total'>
         </form>
        </div>   

        <div class="inline-block normal-bold">Country</div>
        <div class="inline-block">
         <div class="dropdown a-dropdown">
          @php
           $country_names=$countries->pluck('name')->toArray();
           $toggle_text='All';
           $c_input=input('c');
           if(!empty($c_input) && in_array($c_input, $country_names)){
            $toggle_text=$c_input;
           }
          @endphp
          <a href="#" class="btn dropdown-toggle"><span class="toggle-text">{{$toggle_text}}</span><i class="fa fa-caret-down"></i></a>
          <ul class="dropdown-menu">
           @foreach($country_names as $cn)
            <li><a href="{{buildUrlWithParam($url, 'c', $cn)}}" 
            class="refresher menu-item" data-text="{{$cn}}">{{$cn}}</a></li>
           @endforeach
          </ul>
         </div>
        </div>    
      </div>  
    </div>

</div>