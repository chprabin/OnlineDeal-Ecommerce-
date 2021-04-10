@extends('layouts.default')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="normal-bold">Order done</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <div style='margin-bottom:10px;'>
    Your order is successfully registered. please use following transaction ID to track your order:
   </div>
   <div>
    <span style='color:green; font-weight:600px;'>{{$order->payment_batch_number}}</span>
   </div>
  </div>
 </div>
@endsection