<div class="coupon-apply-form">
  <div class="row">
   <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
    <input type="text" name='coupon_code' id='applyable-coupon-code' class='form-control control-block'
    placeholder='insert coupon code'>
   </div>
   <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
    <a href="{{route('coupon_apply')}}" 
    onclick="apply_coupon_code(event, document.getElementById('applyable-coupon-code'))"
    class='btn btn-shop'>
     Apply coupon
    </a>
   </div>
  </div>
</div>