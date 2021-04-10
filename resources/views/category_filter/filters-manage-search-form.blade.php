<h3>Search filters</h3>
<form action="{{route('category_category_filters',['categoryId'=>$category->id])}}" method='GET' 
class='search-form'>
 @php
  $params=reqParams(['wm','wmsf']);
 @endphp
 @foreach($params as $key=>$value)
  <input type="hidden" name="{{$key}}" value="{{$value}}" id="">
 @endforeach
 <input type="text" name='q' placeholder='Search in filters...' class='form-control control-block'>
 <input type="submit" class='hide'>
</form>