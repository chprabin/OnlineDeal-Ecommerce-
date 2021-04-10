@if($data->count())
 <div style='margin:10px 0;'>
  {{$data->links()}} 
 </div>
 <p>
 @php
   $start=($data->currentPage()-1)*$data->perPage()+1;
   $end=($start+$data->perPage()-1)<=$data->total()?($start+$data->perPage()-1):$data->total();
   $string=$start.'-'.$end.' of '.$data->total().' items';
  @endphp
  {{$string}}
 </p>
 <div class="table-wrapper">
  <table>
   <thead>
    <tr class='data-item master'>
     @if(input('wcrud') || input('wselect'))
      <th><span class="select-box"></span></th>
     @endif
     <th>#</th>
     <th>Name</th>
     <th>Amount</th>
     <th>Type</th>
     <th>Expiry date</th>
     <th>Products</th>
     @if(input('wcrud'))
      <th>
       <div class="dropdown a-dropdown">
        <a href="#" class="btn dropdown-toggle"><span class="toggle-text">Bulk actions</span><i style='margin:0 3px;' class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu">
         <li>
          <form action="{{route('promos_delete')}}" method='POST' class='delete-form'>
           @csrf
           @method('DELETE')
           @foreach($data as $key=>$d)
            <input type="hidden" name="ids[{{$key}}]" value="{{$d->id}}">
           @endforeach
           <input type="submit" value="delete all" class='btn'>
          </form>
         </li>
         <li><a href="#" class='menu-item clear-all' data-text='Clear all'>Clear all</a></li>
        </ul>
       </div>
      </th>
     @endif
    </tr>
   </thead>
   <body>
      @php
       $index=($data->currentPage()-1)*$data->perPage()+1;
      @endphp         
      @foreach($data as $d)
       <tr class='data-item' data-id="{{$d->id}}">
        @if(input('wcrud') || input('wselect'))
         <td>
          <span class="select-box"></span>
         </td>
        @endif
        <td>{{$index}}</td>
        <td>{{($d->name)}}</td>
        <td>{{$d->amount}}</td>
        <td>{{$d->type}}</td>
        <td>
         <a href="{{route('promo_promo_products',['promoId'=>$d->id, 'wm'=>1, 'view'=>'vlist'])}}" class="btn"
         onclick='event.preventDefault(); view_promo_products(this.getAttribute("href"));'>View products</a>
        </td>
        @if(input('wcrud'))
         <td>
          <form class='delete-form' action="{{route('promo_delete',['id'=>$d->id])}}" method='POST'>
           @csrf
           @method('DELETE')
           <input type="submit" class='btn' value='delete'>
          </form>
         </td>
        @endif
       </tr>
       @php
        $index++;
       @endphp
      @endforeach
   </body>
  </table>
 </div>
 <div style='margin:10px 0;'>
  {{$data->links()}}
 </div> 
@else 
 <p>no data found</p>
@endif
<script>
 dropdowns();
 var products_modal=null;
 /* products modal */
 function view_promo_products(url){
   if(!products_modal){
     create_component_modal('data-list',{selector:'.promo-products'},function(modal){
        products_modal=modal;
        view_promo_products(url);
     }); 
   }else{
     products_modal.open(url, {}, function(modal){
      modal.init();
     }); 
   }
 }
</script>