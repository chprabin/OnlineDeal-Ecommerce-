@extends('layouts.account')
@section('main')
 <div class="row">
   <div class="col col-12">
    <h1 class="no-margin">New promotion</h1>
   </div> 
 </div>
 <div class="row">
  <div class="col col-8">
   <form action="{{route('promo_save')}}" method='POST' class='promo-form'>
    @csrf
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Name</label>
      <input type="text" name="name" class='form-control control-block' id="">
     </div>
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Type</label>
      @php
       $types=['percent','amount'];
      @endphp
      <select name="type" class='form-control control-block' id="">
      <option value="">Select a type</option>
       @foreach($types as $t)
        <option value="{{$t}}">{{$t}}</option>
       @endforeach
      </select>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Amount</label>
      <input type="text" name="amount" class='form-control control-block' id="">
     </div>
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Expiry date</label>
      <input type="text" name="exdate" class='form-control control-block exdate' id="">
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Products</label>
      <a href="{{route('promo_products',['wm'=>1, 'view'=>'clist', 'wselect'=>1, 'ws'=>1])}}" 
      class="btn btn-block text-center" 
      onclick='event.preventDefault(); manage_promo_products(this.getAttribute("href"));'><span class="fa fa-plus"></span> Add products</a>
      <input type="hidden" name='products' id='products'>
     </div>
    </div>
    <div class="row">
        <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
         <input type="submit" value='save promotion' class='btn btn-primary btn-block' id="">
        </div>
    </div>
   </form>
  </div>
 </div>
@endsection
@section('js')
 <script>
  var products_modal;
  var promo_form=null;

  require(['ajaxform','datepicker'],function(AjaxForm, datepicker){
    promo_form=new AjaxForm({selector:'.promo-form'});
    promo_form.onSend(function(f,r){
        if(r.msg){
            f.addMsg(r.result, r.msg);
        }
        if(r.errors){
            f.addErrors(r.errors);
        }
    });
    promo_form.init();
     var exdp=datepicker('.exdate',{
        formatter:function(input, date){
            input.value=date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate();
        },
    });
  });
  var products_modal=null;
  function manage_promo_products(url){
    if(!products_modal){
        create_component_modal('data-list',{selector:'.promo-products'},function(modal){
            products_modal=modal;
            manage_promo_products(url);
        });
    }else{
        products_modal.on_done(function(modal){
            promo_form.input('products').setAttribute('value',JSON.stringify(modal.component.selectedIds));
        });
        products_modal.open(url, {}, function(modal){
            modal.init();
        });
    }
  }
 </script>
@endsection