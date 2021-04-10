@if($data->count())
 <div style='margin:10px 0;'>
  {{$data->appends(reqParams(['wm','ws','wmfs']))->links()}} 
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
     <th>#</th>
     <th>Filter name</th>
     <th>Filter display text</th>
     <th>Filter type</th>
     <th>Filter url slug</th>
     <th>Filter options</th>
    </tr>
   </thead>
   <body>
      @php
       $index=($data->currentPage()-1)*$data->perPage()+1;
      @endphp         
      @foreach($data as $d)
       <tr class='data-item' data-id="{{$d->id}}">
        <td>{{$index}}</td>
        <td>{{$d->name}}</td>
        <td>{{$d->display_text}}</td>
        <td>{{$d->type}}</td>
        <td>{{$d->url_slug}}</td>
        <td>
         <a href="{{route('filter_options',['filterId'=>$d->id, 'view'=>'vlist', 'wm'=>1, ])}}" class="btn"
         onclick="event.preventDefault(); view_options(this.getAttribute('href'));">view options</a>
        </td>
       </tr>
       @php
        $index++;
       @endphp
      @endforeach
   </body>
  </table>
 </div>
 <div style='margin:10px 0;'>
 {{$data->appends(reqParams(['wm','ws','wmfs']))->links()}} 
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