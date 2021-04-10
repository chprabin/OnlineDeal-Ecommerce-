@if($product->images->count())
 <h3>Available images</h3>

 <div class="row">
  @foreach($product->images as $d)
   <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-4">
    <div class="data-item" data-id="{{$d->id}}">
     <div class="row">
      <div class="col col-12"><img src="{{asset($d->image)}}" alt="" style='width:100%;'></div>
     </div>
    </div>
   </div>
  @endforeach
 </div>
@else
 <p>no images found</p>
@endif