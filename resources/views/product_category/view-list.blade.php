@if($data->count())
 <div style='margin:10px 0;'>
  {{$data->appends(reqParams(['wm','wmcs']))->links()}} 
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
     <th>Category name</th>
     <th>Category showname</th>
     <th>Category parent</th>
     <th>Category filters</th>
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
        <td>{{$d->showname}}</td>
        <td>{{!empty($d->parentCategory) ? $d->parentCategory->showname:'-'}}</td>
        <td>
         <a href="{{route('category_category_filters',['categoryId'=>$d->id, 'wm'=>1, 'wview'=>1, 'view'=>'fview'])}}" class="btn"
         onclick="event.preventDefault(); view_category_filters(this.getAttribute('href'));">view filters</a>
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
 {{$data->appends(reqParams(['wm','wmcs']))->links()}} 
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