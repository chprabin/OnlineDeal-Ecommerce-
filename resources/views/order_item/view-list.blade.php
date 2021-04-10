@if($data->count())
 <div style='margin:20px 0;'>
  {{$data->appends(reqParams(['wm','ws']))->links()}}
 </div>
 @php
  $item_number=($data->currentPage()-1)*$data->perPage()+1;
 @endphp
 <div class="table-wrapper">
  <table>
   <thead>
    <div class="data-item master-item">
     <th>#</th>
     <th>Product name</th>
     <th>Product price</th>
     <th>Item quantity</th>
     <th>Item total</th>
    </div>
   </thead>
   <tbody>
    @foreach($data as $d)
     <tr class='data-item' data-id="{{$d->id}}">
      <td>{{$item_number}}</td>
      <td>{{shorten_text($d->product->name, 100)}}</td>
      <td>${{$d->product->price}}</td>
      <td>{{$d->quantity}}</td>
      <td>${{$d->product->price * $d->quantity}}</td>
     </tr>
     @php
      $item_number++;
     @endphp
    @endforeach
   </tbody>
  </table>
 </div>
 <div style='margin:20px 0;'>
 {{$data->appends(reqParams(['wm','ws']))->links()}}
 </div>
@else
<p>
 no data found
</p>
@endif