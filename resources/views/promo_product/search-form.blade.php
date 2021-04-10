<h3>Search</h3>
<form action="{{route('promo_products')}}" method='GET' class='search-form'>
 @foreach(reqParams(['wm','ws']) as $n=>$v)
  <input type="hidden" name='{{$n}}' value="{{$v}}">
 @endforeach
 <input type="text" name='q' class='form-control control-block' placeholder='Search in products...'>
</form>