@extends('layouts.default')
@section('main')
<style>
 .checkout .checkout-section{
   padding:0 1%;
 }
 .checkout .checkout-section.with-border{
   border-right:1px solid #ccc;
 }
</style>
  <div class="row">
   <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-12">
    <h1 class="no-margin">Order checkout</h1>
   </div>
   <div class="col col-lg-9 col-md-9 col-xs-12 col-sm-12">
    <p>
     Please provide information below to complete your order
    </p>
   </div>
  </div>

  <div class="checkout">
   <form action="{{route('order_register')}}" method='POST'>
    @csrf
    <input type="hidden" name='total' value="{{number_format($cart->getOrderTotal(), 2, '.', '')}}">
    <input type="hidden" name='userId' value="{{auth()->user()->id}}">
    <div class="row">
     <!-- billing information -->
      <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
       <div class="checkout-section with-border">
        <h3 class="section-title"><span style='color:orange;'>1. </span><span>Billing information</span></h3>
        <div class="section-content">
         <div class="row">
          <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
           <label for="" class="block">Firstname</label>
           <input type="text" name='firstname' value='{{old("firstname")}}' class='form-control control-block'>
           @if($errors->has('firstname'))
            <div class="form-error">{{$errors->first('firstname')}}</div>
           @endif
          </div>
          <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
           <label for="" class="block">Lastname</label>
           <input type="text" name='lastname' value='{{old("lastname")}}' class='form-control control-block'>
           @if($errors->has('lastname'))
            <div class="form-error">{{$errors->first('lastname')}}</div>
           @endif
          </div> 
         </div>
         <div class="row">
          <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
            <label for="" class="block">Email address</label>
            <input type="text" name='email' value='{{old("email")}}' class='form-control control-block'>
            @if($errors->has('email'))
             <div class="form-error">{{$errors->first('email')}}</div>
            @endif
          </div>
          <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
            <label for="" class="block">Phone</label>
            <input type="text" name='phone' value='{{old("phone")}}' class='form-control control-block'>
            @if($errors->has('phone'))
             <div class="form-error">{{$errors->first('phone')}}</div>
            @endif
          </div>
         </div>
         <div class="row">
          <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
           <label for="" class="block">Country</label>
           <select name="countryId" class='form-control control-block'>
            <option value="">Select a country</option>
            @foreach($countries as $c)
             @php
              $selected=$c->id==old('countryId')?"selected='selected'":null;
             @endphp
             <option value="{{$c->id}}" {{$selected}}>{{$c->name}}</option>
            @endforeach
           </select>
          </div>
          <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
            <label for="" class="block">City</label>
            <input type="text" name='city' value='{{old("city")}}' class='form-control control-block'>
            @if($errors->has('city'))
             <div class="form-error">{{$errors->first('city')}}</div>
            @endif
          </div>
         </div>
         <div class="row">
          <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
            <label for="" class="block">Street</label>
            <input type="text" name='street' value='{{old("street")}}' class='form-control control-block'>
            @if($errors->has('street'))
             <div class="form-error">{{$errors->first('street')}}</div>
            @endif
          </div>
         </div>
        </div>
       </div>
      </div>
     <!-- end of billing information -->
     <!-- payment method -->
      <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
       <div class="checkout-section with-border">
        <h3 class="section-title"><span style='color:orange;'>2. </span><span>Payment methods</span></h3>
        <div class="section-content">
         <div class="row">
          <div class="col col-12">
           <label for=""><input type="radio" checked='checked'> Perfect money</label>
          </div>
         </div>
        </div>
       </div>
      </div>
     <!-- end of payment method -->
     <!-- order review -->
      <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
       <div class="checkout-section">
        <h3 class="section-title"><span style='color:orange;'>3. </span><span>Order review</span></h3>
        <div class="section-content">
         <div style='background:#ededed; padding:10px 3%;'>
          <div id="cart-summary">
           @include('cart.cart-summary',['cart'=>$cart])
          </div>
          <div>
           @include('coupon.apply-form')
          </div>
          <div class="row">
           <div class="col col-12">
            <input type="submit" class='btn btn-primary' value='Place order'>
           </div>
          </div>
         </div>
        </div>
       </div>
      </div>
     <!-- end of order review -->
    </div>    
   </form>
  </div>
  @include('partials.personalized-recommendations-slider')

@endsection     
@section('js')
 <script>
  var request=null;
  require(['request'],function(Req){
    request=new Req();
  });
  adjustHeights('.checkout .checkout-section');
  disable_form_submit_by_enter();
  function apply_coupon_code(event, codeElem){
    event.preventDefault();
    var code=codeElem.value;
    if(code){ 
      var res=request.post(event.target.getAttribute('href'), {coupon_code:code});
      if(res.result){
        flashSuccess(res.msg);
        $('#cart-summary').html(res.view);
      }else{
        flashFail(res.msg);
      }
    }
  }

  function disable_form_submit_by_enter(){
    document.addEventListener('keydown',function(e){
      if(e.keyCode==13){
        e.preventDefault();
        return false;
      }
    });
  }
 </script>
@endsection