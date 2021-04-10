<div class="group">
 <div class="row">
  @foreach($data as $d)
   <div class="col col-lg-2 col-md-2 col-xs-12 col-sm-6">
    @include('partials.home-product-group-slider-item',['d'=>$d])
   </div>
  @endforeach
 </div>
</div>

<script>
 adjustHeights('.product-group div.title');
</script>