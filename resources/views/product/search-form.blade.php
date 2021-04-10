<h3>Search products</h3>
<form action="{{route('products_management')}}" method='GET' class='search-form'>
 <input type="hidden" name="wcrud" value='1'>
 <input type="text" name='q' placeholder='Search in products...' class='form-control control-block'>
</form>