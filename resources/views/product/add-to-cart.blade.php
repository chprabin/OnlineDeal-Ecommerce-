<form action="{{route('cart_save')}}" method='POST' class='add-to-cart'>
 @csrf
 <input type="hidden" name='productId' value='{{$product->id}}'>
 <div class="row">
  <label for="" class="col col-lg-6 col-m-6 col-xs-12 col-sm-12">Quantity</label>
  <select name="quantity" id="" class='form-control col col-lg-6 col-md-6 col-xs-12 col-sm-12'>
   @foreach(range(1,$product->stock) as $ps)
    <option value="{{$ps}}">{{$ps}}</option>
   @endforeach
  </select>
 </div>
 <div class="row">
  <input type="submit" value='Add to cart' class='col col-12 btn btn-shop'>
 </div>
</form>