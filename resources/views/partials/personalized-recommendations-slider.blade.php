@if(auth()->check())
 <style>
  .prsw .rotator{
      background:transparent;
      top:26%;
      font-size:49px;
  }
  .prsw .right-rotator{
      right:16px;
  }
 </style>
 <div class="row">
  <div class="col col-12">
   <div style='margin:10px 0;'>
    <div class="row">
     <div class="col col-12">
      <h2 class="normal-bold no-margin">Personalized recommendations</h2>
     </div>
    </div>
    <div class="slider-wrapper prsw">
      <div class="row">
       <div class="col col-offset-lg-1 col-offset-md-1 col-lg-11 col-md-11 col-xs-12 col-sm-12">
        <div class="slider cf">
         @include('partials.personalized-recommendations-slider-group',['data'=>$personalized_recommendations])
        </div>
       </div>
      </div>
      <a href="#" class="rotator left-rotator"><span class="fa fa-angle-left"></span></a>
      <a href="#" class="rotator right-rotator"><span class="fa fa-angle-right"></span></a>  
    </div>
   </div>
  </div>
 </div>
@else
 <div class="row">
  <div class="col col-12">
   <h2 class="no-margin- normal-bold">Please login to see personalized recommendations</h2>
  </div>
 </div>
@endif
<script>
 require(['slider','request'],function(Slider, Request){
    var req=new Request();
    var slider=new Slider({selector:'.prsw'});
    slider.on_data_load(function(slider){
      var res=req.get('/personalized-recommendations',{page:slider.current_page+1, per_page:4});
      if(res.result){
        slider.extend(res.view);
      }
    });
    slider.init();
 });
</script>