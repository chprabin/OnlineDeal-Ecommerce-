<h3>Search</h3>
<form action="{{route('order_items',['orderId'=>$order->id])}}" method='GET' class='search-form'>
 <input type="hidden" name='view' value='vlist'>
 <input type="text" name='q' class='form-control control-block' placeholder='Search in order items...'>
</form>