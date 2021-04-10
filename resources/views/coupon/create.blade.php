@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1>New coupon</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-lg-8 col-md-8 col-xs-12 col-sm-12">
   <div class="row">
    <div class="col col-12">
     <form action="{{route('coupon_save')}}" method='POST' class='coupon-form'>
      @csrf
      <div class="row">
       <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
        <label for="" class="block">Coupon name</label>
        <input type="text" name='name' class='form-control control-block' >
       </div>

       <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
        <label for="" class="block">Coupon type</label>
        <select name="type" class="form-control control-block">
         <option value="">select a type</option>
         @foreach(['amount','percent'] as $type)
          <option value="{{$type}}">{{$type}}</option>
         @endforeach
        </select>
       </div>

      </div>

      <div class="row">
        <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
         <label for="" class="block">Coupon amount</label>
         <input type="text" name='amount' class='form-control control-block' >
        </div>

        <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
         <label for="" class="block">Coupon start date</label>
         <input type="text" name='sdate' class='form-control control-block sdate' placeholder='select start date...'>
        </div>
            
      </div>

      <div class="row">
        <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
         <label for="" class="block">Coupon end date</label>
         <input type="text" name='edate' class='form-control control-block edate' placeholder='select end date...'>
        </div>

        <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
         <label for="" class="block">Coupon minimum total</label>
         <input type="text" name='min_total' class='form-control control-block' value='0.00'>
        </div>
         
      </div>

      <div class="row">
        <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
         <label for="" class="block">Coupon maximum usage</label>
         <input type="text" name='max_usage' class='form-control control-block' value='0'>
        </div>
      </div>

      <div class="row">
        <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
         <input type="submit" value='save coupon' class='btn btn-block btn-primary'>
        </div>
      </div>
     </form>
    </div>
   </div>
  </div>
 </div>
@endsection
@section('js')
 <script>
  require(['ajaxform','datepicker'],function(AjaxForm, dp){
    var coupon_form=new AjaxForm({selector:'.coupon-form'});
    coupon_form.onSend(function(f,r){
        if(r.msg){
         f.addMsg(r.result,r.msg);
        }
        if(r.errors){
         f.addErrors(r.errors);
        }
    });
    coupon_form.init();

    var sdate=dp('.sdate',{id:1,
    formatter:function(input, date){
     input.value=date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate();
    }
   });
   var edate=dp('.edate',{id:1,
    formatter:function(input, date){
     input.value=date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate();
    }
   });
  });
 </script>
@endsection