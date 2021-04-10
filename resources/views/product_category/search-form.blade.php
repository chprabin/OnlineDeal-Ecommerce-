<h3>Search categories</h3>
<form action="{{route('categories_management')}}" method='GET' class='search-form'>
 @php
  $params=reqParams(['wm','ws']);
 @endphp
 @foreach($params as $key=>$value)
  <input type="hidden" name='{{$key}}' value='{{$value}}'>
 @endforeach
 <input type="text" name='q' placeholder='Search in categories...' class='form-control control-block'>
</form>