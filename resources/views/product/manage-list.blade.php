@if($data->count())
 <div style='margin:10px 0;'>
  {{$data->links()}} 
 </div>
 <div style='margin:10px 0;'>
  @include('product.data-filter')
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
     <th>Brand</th>
     <th>Price</th>
     <th>Categories</th>
     <th>Images</th>
     <th>Options</th>
     @if(input('wcrud'))
      <th>
       <div class="dropdown a-dropdown">
        <a href="#" class="btn dropdown-toggle"><span class="toggle-text">Bulk actions</span><i style='margin:0 3px;' class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu">
         <li>
          <form action="{{route('products_delete')}}" method='POST' class='delete-form'>
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
        <td>{{shorten_text($d->name)}}</td>
        <td>{{$d->brand->name}}</td>
        <td>{{'$'.$d->price}}</td>
        <td>
         <a href="{{route('product_product_categories',['productId'=>$d->id, 'wm'=>1, 'view'=>'vlist', 'wview'=>1])}}" class="btn"
         onclick='event.preventDefault(); view_product_categories(this.getAttribute("href"));'>View categories</a>
        </td>
        <td>
         <a href="{{route('product_product_images',['productId'=>$d->id, 'wm'=>1, 'view'=>'vlist'])}}" class="btn"
         onclick='event.preventDefault(); view_product_images(this.getAttribute("href"));'>View images</a>
        </td>
        <td>
         <a href="{{route('product_product_options',['productId'=>$d->id, 'wm'=>1, 'view'=>'vlist'])}}" class="btn"
         onclick='event.preventDefault(); view_product_options(this.getAttribute("href"));'>View options</a>
        </td>
        @if(input('wcrud'))
         <td>
          <a href="{{route('product_edit',['id'=>$d->id])}}" class='btn left' style='margin-right:3px;  '>edit</a>
          <form class='delete-form' action="{{route('product_delete',['id'=>$d->id])}}" method='POST'>
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
  @include('product.data-filter')
 </div>
 <p>no data found</p>
@endif
<script>
 dropdowns();
 var options_modal=null;
 var categories_modal=null;
 var images_modal=null;
 /* options view */
 function view_product_options(url){
   if(!options_modal){
     create_component_modal('data-list',{selector:'.product-options'},function(modal){
        options_modal=modal;
        view_product_options(url);
     }); 
   }else{
     options_modal.open(url, {}, function(modal){
      modal.init();
     }); 
   }
 }

 /* categories view */
 function view_product_categories(url){
   if(!options_modal){
     create_component_modal('data-list',{selector:'.product-categories'},function(modal){
        options_modal=modal;
        view_product_categories(url);
     }); 
   }else{
     options_modal.open(url, {}, function(modal){
      modal.init();
     }); 
   }
 }

 /* images view */
 function view_product_images(url){
   if(!options_modal){
     create_component_modal('data-list',{selector:'.product-images'},function(modal){
        options_modal=modal;
        view_product_images(url);
     }); 
   }else{
     options_modal.open(url, {}, function(modal){
      modal.init();
     }); 
   }
 }
</script>