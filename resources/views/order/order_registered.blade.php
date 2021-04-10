@extends('layouts.default')
@section('main')
  <div class="row">
   <div class="col col-12">
    <h1 class="no-margin">Order registered</h1>
   </div>
  </div>

 <div class="row">
   <div class="col col-lg-8 col-md-8 col-xs-12 col-sm-12 col-offset-lg-2 col-offset-md-2">
     <p>
      Your order is successfully registered. please use following transaction ID to tack your order:
      <span class="block" style='color:green;font-size:18px; margin:10px 0;'>{{$order->payment_batch_number}}</span>
     </p>
     <p>
      <a href="{{route('order_track')}}" class="btn">Track order</a>
     </p>      
   </div> 
 </div> 
@endsection     
 