@extends('layouts.default')
@section('main')
  <div class="row">
   <div class="col col-12">
    <h1 class="no-margin">Track your order</h1>
   </div>
  </div>

  @if(!empty($order))
   <div class="row">
    <div class="col col-12">
     <div style='color:green;'>Your order state: {{$order->state->name}}</div>
    </div>
   </div>
  @endif  

  <div class="row">
    <form action="{{route('order_track')}}" method='GET'
     class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <div class="row">
       <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
        <input type="text" name='batch_number' placeholder='enter transaction ID...' class='form-control control-block'>
       </div>
       <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
        <input type="submit" value='Track order' class='btn btn-shop btn-block'>
       </div>
      </div>
     </form>
  </div>
@endsection     
 