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
     <th>Category name</th>
     <th>Category showname</th>
     <th>Category parent</th>
     <th>Category filters</th>
     @if(input('wcrud'))
      <th>
       <div class="dropdown a-dropdown">
        <a href="#" class="btn dropdown-toggle"><span class="toggle-text">Bulk actions</span><i style='margin:0 3px;' class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu">
         <li>
          <form action="{{route('categories_delete')}}" method='POST' class='delete-form'>
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
        <td>{{$d->name}}</td>
        <td>{{$d->showname}}</td>
        <td>{{!empty($d->parentCategory) ? $d->parentCategory->showname:'-'}}</td>
        <td>
         <a href="{{route('category_category_filters',['categoryId'=>$d->id, 'wm'=>1, 'wview'=>1, 'view'=>'fview'])}}" class="btn"
         onclick="event.preventDefault(); view_category_filters(this.getAttribute('href'));">view filters</a>
        </td>
        @if(input('wcrud'))
         <td>
          <a href="{{route('category_edit',['id'=>$d->id])}}" class='btn left' style='margin-right:3px;'>edit</a>
          <form class='delete-form' action="{{route('category_delete',['id'=>$d->id])}}" method='POST'>
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
 /* category filters view */
 var filters_modal=null;
 function view_category_filters(url){
   if(!filters_modal){
      create_component_modal('data-list',{selector:'.category-filters'},function(modal){
         filters_modal=modal;
         view_category_filters(url);
      });   
   }else{
     filters_modal.open(url, {}, function(modal){
      modal.open();
     });    
   }
 }
 dropdowns();
</script>