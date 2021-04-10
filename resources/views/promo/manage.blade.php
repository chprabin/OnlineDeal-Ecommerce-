@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin no-bold">Promotions management</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <a href="{{route('promo_create')}}" class="btn"><i class="fa fa-plus"></i> Create new promotion</a>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <div class="data">
    <div class="updatable-view">
     @include('promo.manage-list',['data'=>$data])   
    </div>
   </div>
  </div>
 </div>
@endsection
@section('js')
 <script>
  require(['data-list'],function(DataList){
    var dl=new DataList({selector:'.data'});
    dl.init();
  });
 </script>
@endsection