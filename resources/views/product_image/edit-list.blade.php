@if($product->images->count())
 <h3>Available images</h3>
 <div class="row">
  @foreach($product->images as $d)
    <div class="col col-lg-3 col-md-3 col-sm-4 col-xs-12">
     <div class="data-item" data-id="{{$d->id}}">
      <div class="row">
       <div class="col col-12"><img src="{{asset($d->image)}}" style='width:150px; height:150px; ' alt=""></div>
      </div>
      <div class="row">
       <div class="col"><span class="select-box"></span></div>
       <div class="col">
        <form action="{{route('product_image_delete',['id'=>$d->id])}}" method='POST' class='delete-form'>
         @csrf
         @method('DELETE');
         <input type="submit" class='btn' value='delete'>        
        </form>
       </div>
      </div>
     </div>
    </div>
  @endforeach
 </div>
@endif
@if(isset($inserted_data) && count($inserted_data))
  @include('product_image.create-list',['inserted_data'=>$inserted_data])  
@endif