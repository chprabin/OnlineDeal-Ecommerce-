@if($data->count())
 <div style='margin:10px 0;'>
  {{$data->appends(reqParams(['wm','wmsf']))->links()}} 
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
      <th><span class="select-box">
      </span></th>
     @endif
     <th>#</th>
     <th>Filter name</th>
     <th>Filter display text</th>
     <th>Filter type</th>
     <th>Filter url slug</th>
     <th>Filter options</th>
     @if(input('wcrud'))
      <th>
       <div class="dropdown a-dropdown">
        <a href="#" class="btn dropdown-toggle"><span class="toggle-text">Bulk actions</span>
        <i style='margin:0 3px;' class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu">
         <li>
          <form action="{{route('category_filters_delete',['categoryId'=>$category->id])}}"
           method='POST' class='delete-form'>
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
          <span class="select-box {{$category_filters->pluck('id')->contains($d->id)?'selected':null}}"></span>
         </td>
        @endif
        <td>{{$index}}</td>
        <td>{{$d->name}}</td>
        <td>{{$d->display_text}}</td>
        <td>{{$d->type}}</td>
        <td>{{$d->url_slug}}</td>
        <td>
         <a href="{{route('filter_options',['filterId'=>$d->id, 'view'=>'vlist', 'wm'=>1,])}}" class="btn"
         onclick="event.preventDefault(); view_options(this.getAttribute('href'));">view options</a>
        </td>
        @if(input('wcrud'))
         <td>
          <a href="{{route('filter_edit',['id'=>$d->id])}}" class='btn left' style='margin-right:3px;'>edit</a>
          @if($category_filters->pluck('id')->contains($d->id))
          <form class='delete-form' action="{{route('category_filter_delete',
          ['categoryId'=>$category->id,'filterId'=>$d->id])}}" method='POST'>
           @csrf
           @method('DELETE')
           <input type="submit" class='btn' value='delete'>
          </form>
          @endif
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
 {{$data->appends(reqParams(['wm','wmsf']))->links()}}   
 </div> 
@else
 <p>no data found</p>
@endif
<script>
 dropdowns();
 var options_modal=null;

 function view_options(url){
   if(!options_modal){
     create_component_modal('data-list',{selector:'.options'},function(modal){
        options_modal=modal;
        view_options(url);
     }); 
   }else{
     options_modal.open(url, {}, function(modal){
      modal.init();
     }); 
   }
 }
</script>