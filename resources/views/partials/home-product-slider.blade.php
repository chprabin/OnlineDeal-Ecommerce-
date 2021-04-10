<div class="slider-wrapper {{$slider_wrapper_class}}">
   <h2 class='slider-title'>{{$slider_title}}</h2>
  <div class="slider">
   @include($slider_group_partial,['data'=>$data])
  </div>
  <div class="rotators">
   <a href="#" class="rotator left-rotator"><span class="fa fa-angle-left"></span></a>
   <a href="#" class="rotator right-rotator"><span class="fa fa-angle-right"></span></a>
  </div>
</div>
<script>
 var slider_wrapper_class="{{$slider_wrapper_class}}";
 var req=null;
 var remote_url="{{$remote_url}}";
 require(['request'],function(Request){
    req=new Request();
 });
 createComponent('slider',{selector:"."+slider_wrapper_class},function(slider){
    slider.on_data_load(function(slider){
        var res=req.get(remote_url, {page:slider.current_page+1});
        if(res.result){
         slider.extend(res.view);
         return true;
        }
    });
    slider.init();
 });
</script>