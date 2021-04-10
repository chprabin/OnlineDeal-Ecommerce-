<h3>Search categories</h3>
<form action="{{route('product_product_categories',['productId'=>$product->id])}}" method='GET' class='search-form'>
 @php
  $params=reqParams(['wm','wmcs']);
 @endphp
 @foreach($params as $name=>$value)
  <input type="hidden" name="{{$name}}" value='{{$value}}' id="">
 @endforeach
 <input type="text" name='q' placeholder='Search in categories...' class='form-control control-block'>
</form>