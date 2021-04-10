<!DOCTYPE html>
<html>
 <head>
 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" id='csrf' content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/public.css')}}">
    <link rel="stylesheet" href="{{asset('css/grid.css')}}">
    <link rel="stylesheet" href="{{asset('fa/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
    <link rel="stylesheet" href="{{asset('css/account.css')}}">
    <script src='{{asset("js/helpers.js")}}'></script>
    <script src="{{asset('js/require.js')}}" data-main="{{asset('js/main.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/datepicker.css')}}">
 </head>
 <body>
   @include('partials.header')
   <div class="main account-main cf">
    <div class="left account-sidebar">
     <div class="row">
      <div class="col col-12">
        @include('partials.account-sidebar')      
      </div>
     </div>
    </div>
    <div class="left account-main-content">
     <div class="row">
      <div class="col col-12">
       @yield('main')
      </div>
     </div>
    </div>
   </div>     
   @include('partials.footer')
   <script src="{{asset('js/jquery.js')}}"></script>
   @yield('js')
   <script>
    setInterval(function(){
    adjustSidebarHeight();
    },200)
    function adjustSidebarHeight(){
      var main_content_height=$('.account-main-content').height();
      $('.account-sidebar').css({height:main_content_height+'px'});
    }
   </script>
 </body>
</html>