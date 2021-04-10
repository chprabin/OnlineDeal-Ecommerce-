@extends('layouts.account')
@section('main')

 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin normal-bold">Statistics</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <div class="dropdown a-dropdown">
    @php
     $toggle_text='january';
     if(!empty($month) && in_array($month, array_values($monthes))){
        $toggle_text=$month;
     }
    @endphp
     <span style='margin-right:10px;'>Show data of </span>
     <a href="#" class="btn dropdown-toggle" style='text-transform:capitalize;'>
     <span class="toggle-text" style='margin-right:4px;'>{{$toggle_text}}</span> <i class="fa fa-caret-down"></i></a>
     <ul class="dropdown-menu">
      @php
       $url=get_full_url();
      @endphp
      @foreach($monthes as $m)
       <li><a href="{{buildUrlWithParam($url, 'month', $m)}}" class='menu-item' style='text-transform:capitalize;' data-text="{{$m}}"
       onclick='event.preventDefault(); update_reports(this.getAttribute("href"))'>{{$m}}</a></li>
      @endforeach
     </ul>
   </div>  
  </div>
 </div>
 <div class="reports-updatable">
  @include('report.reports-updatable')
 </div>
@endsection

@section('js')
 <script>
  dropdowns();
  var req=null;
  require(['request'],function(Request){
    req=new Request();
  });
  function update_reports(url){
    var res=req.get(url);
    addAjaxLoader();
    if(res.view){
        $('.reports-updatable').html(res.view);
    }
    removeAjaxLoader();
  }
 </script>
@endsection