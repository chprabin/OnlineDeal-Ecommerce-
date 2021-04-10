@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin no-bold">Edit brand</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
   <form class='brand-form' action="{{route('brand_update',['id'=>$model->id])}}" method='POST'>
    @csrf
    @method('PUT')
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Brand name</label>
      <input type="text" name='name' value="{{$model->name}}" class='form-control control-block'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <input type="submit" class='btn btn-block btn-primary' value='Save brand'>
     </div>
    </div>
   </form>
  </div>
 </div> 
@endsection
@section('js')
 <script>
  createComponent('ajaxform',{selector:'.brand-form'},function(form){
    form.onSend(function(f,r){
      if(r.result){
        f.addMsg(r.result, r.msg);
      }
      if(r.errors){
        f.addErrors(r.errors);
      }
    });
    form.init();
  });
 </script> 
@endsection