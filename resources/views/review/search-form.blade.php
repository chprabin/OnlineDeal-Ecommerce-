<h3>Search reviews</h3>
<form action="{{route('reviews_management')}}" method='GET' class='search-form'>
 <input type="hidden" name='wcrud' value='1'>
 <input type="text" name="q" placeholder='Search in reviews...' class='form-control control-block'>
</form>