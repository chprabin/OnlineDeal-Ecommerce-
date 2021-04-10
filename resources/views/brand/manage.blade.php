@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin no-bold">Brands management</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <a href="{{route('brand_create')}}" class="btn"><i class="fa fa-plus"></i> Create new brand</a>
  </div>
  <div class="col col-12">
   <h2 class="no-margin no-bold">Search</h2>
  </div>
  <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
   <form action="{{route('brands_management')}}" method='GET'>
    <input type="hidden" name='wcrud' value="1">
    <input type="text" placeholder='Search in brands' class='form-control control-block'>
   </form>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <div class="data">
    <div class="updatable-view">
     @include('brand.manage-list',['data'=>$data])   
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