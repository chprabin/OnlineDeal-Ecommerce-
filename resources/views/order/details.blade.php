@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin">Order details</h1>
  </div>
 </div>
 <div class="row">
    <div class="col col-12">
     <form action="{{route('order_update',['id'=>$model->id])}}" method='POST' class='order-form'>
      @csrf
      @method('PUT')
      <div class="row">
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">Firstname</label>
        <input type="text" name='firstname' class='form-control control-block' value="{{$model->firstname}}" disabled='disabled'>
       </div>
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">Lastname</label>
        <input type="text" name='lastname' class='form-control control-block' value="{{$model->lastname}}" disabled='disabled'>
       </div>
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">Email address</label>
        <input type="text" name='email' class='form-control control-block' value="{{$model->email}}" disabled='disabled'>
       </div>
      </div>
      <div class="row">
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
         <label for="" class="block">Country</label>
         <input type="text" class='form-control control-block' value="{{$model->country->name}}" disabled='disabled'>
        </div>
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">City</label>
        <input type="text" name='city' class='form-control control-block' value="{{$model->city}}" disabled='disabled'>
       </div>
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">Street</label>
        <input type="text" name='firstname' class='form-control control-block' value="{{$model->street}}" disabled='disabled'>
       </div>
      </div>
      <div class="row">
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">Phone</label>
        <input type="text" name='firstname' class='form-control control-block' value="{{$model->phone}}" disabled='disabled'>
       </div>
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">Order total</label>
        <input type="text" name='firstname' class='form-control control-block' value="${{number_format($model->total, 2, '.', '')}}" disabled='disabled'>
       </div>
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">State</label>
        <select name="stateId" class='form-control control-block'>
         @foreach($states as $s)
          @php
           $selected=$s->id==$model->stateId?"selected='selected'":null;
          @endphp
          <option value="{{$s->id}}" {{$selected}}>{{$s->name}}</option>
         @endforeach
        </select>
       </div>
      </div>
      <div class="row">
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">Payment batch number</label>
        <input type="text" name='firstname' class='form-control control-block' value="{{$model->payment_batch_number}}" disabled='disabled'>
       </div>
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <label for="" class="block">Order items</label>
        <a href="{{route('order_items',['orderId'=>$model->id, 'ws'=>1, 'wm'=>1, 'view'=>'vlist'])}}" 
        class="btn btn-block text-center" 
        onclick="event.preventDefault(); view_order_items(this.getAttribute('href'));">View items</a>
       </div>
      </div>
      <div class="row">
       <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
        <input type="submit" value='save order' class='btn btn-primary btn-block'/> 
       </div>
      </div>
     </form>
    </div>
 </div>
@endsection 
@section('js')
 <script>
  var items_view_modal=null;
  function view_order_items(url){
  if(!items_view_modal){
    create_component_modal('data-list',{selector:'.order-items'},function(modal){
        items_view_modal=modal;
        view_order_items(url);
    });
  }else{
     items_view_modal.open(url, {}, function(modal){
        modal.init();
     }); 
  }
}
  createComponent('ajaxform',{selector:'.order-form'},function(af){
    af.onSend(function(f,r){
      if(r.msg){
        f.addMsg(r.result, r.msg);
      }  
    });
    af.init();
  });
 </script>
 @endsection