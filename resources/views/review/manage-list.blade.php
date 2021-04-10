@if($data->count())
 <div style='margin:10px 0;'>
  {{$data->links()}} 
 </div>
 <div style='margin:10px 0;'>
  @include('review.data-filter')
 </div>
 <p>
  @php
   $start=($data->currentPage()-1)*$data->perPage()+1;
   $end=($start+$data->perPage())<=$data->total()?($start+$data->perPage()):$data->total();
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
     <th>title</th>
     <th>review</th>
     <th>rating</th>
     <th>user</th>
     <th>product</th>
     @if(input('wcrud'))
      <th>
       <div class="dropdown a-dropdown">
        <a href="#" class="btn dropdown-toggle"><span class="toggle-text">Bulk actions</span><i style='margin:0 3px;' class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu">
         <li>
          <form action="{{route('reviews_delete')}}" method='POST' class='delete-form'>
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
        <td>{{shorten_text($d->title)}}</td>
        <td>{{shorten_text($d->review)}}</td>
        <td>{{$d->rating}}</td>
        <td>
         <a href="#" class="btn">view user</a>
        </td>
        <td>
         <a href="{{route('product_details',['productId'=>$d->product->id, 'productName'=>$d->product->name,'view'=>'quicklook','wm'=>1])}}" class='btn'
         onclick='event.preventDefault(); view_product_details(this.getAttribute("href"));'>view product</a>
        </td>
        @if(input('wcrud'))
         <td>
          <form class='delete-form' action="{{route('review_delete',['id'=>$d->id])}}" method='POST'>
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
 <div style='margin:10px 0;'>
  @include('review.data-filter')
 </div>
 <p>no data found</p>
@endif
<script>
 dropdowns();
 var product_details_modal=null;
 function view_product_details(url){
   if(!product_details_modal){
      createComponent('modal',{},function(modal){
         product_details_modal=modal;
         view_product_details(url);
      });
   }else{
      product_details_modal.open(url);
   }
 }
</script>