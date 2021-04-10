<html>
 <head>
 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{asset('css/public.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/grid.css')}}">
    <link rel="stylesheet" href="{{asset('fa/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
    <script src='{{asset("js/helpers.js")}}'></script>
    <script src='{{asset("js/jquery.js")}}'></script>
    <script src="{{asset('js/require.js')}}" data-main="{{asset('js/main.js')}}"></script>
    <meta name="csrf-token" id='csrf' content="{{ csrf_token() }}">
    @yield('css')
 </head>
 <body>
   @include('partials.header')
   <div class="main">
    @yield('main')
   </div>     
   @include('partials.footer')
   @yield('js')
 </body>
</html>