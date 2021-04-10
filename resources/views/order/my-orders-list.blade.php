@if($data->count())
 <div style='margin:10px 0;'>
  {{$data->links()}} 
 </div>
 <div style='margin:10px 0;'>
  @include('order.data-filter')
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
     <th>#</th>
     <th>Firstname</th>
     <th>Lastname</th>
     <th>Total</th>
     <th>Country</th>
     <th>State</th>
     <th>Items</th>
    </tr>
   </thead>
   <body>
      @php
       $index=($data->currentPage()-1)*$data->perPage()+1;
      @endphp         
      @foreach($data as $d)
       <tr class='data-item' data-id="{{$d->id}}">
        <td>{{$index}}</td>
        <td>{{$d->firstname}}</td>
        <td>{{$d->lastname}}</td>
        <td>${{number_format($d->total, 2, '.', '')}}</td>
        <td>{{$d->country->name}}</td>
        <td>{{$d->state->name}}</td>
        <td>    
         <a href="{{route('order_items',['orderId'=>$d->id, 'view'=>'vlist', 'ws'=>1, 'wm'=>1])}}" 
         class="btn" onclick='event.preventDefault(); view_order_items(this.getAttribute("href"));'>View items</a>
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
  {{$data->links()}}
 </div> 
@else
 <div style='margin:10px 0;'>
  @include('order.data-filter')
 </div>
 <p>
    no data found
 </p>
@endif
<script>
 dropdowns();
 var items_view_modal=null;   
 function view_order_items(url){
    if(!items_view_modal){
        create_component_modal('data-list',{selector:'.order-items'},function(modal){
            items_view_modal=modal;
            view_order_items(url);
        });
    }else{
        items_view_modal.open(url, {}, function(modal){
            modal.init();
        });
    }
 }

 require(['ajaxform'],function(AjaxForm){
    var partial_update_forms=document.querySelectorAll('.partial-update-form');
    partial_update_forms.forEach(function(form){
        var af=new AjaxForm();
        af.setForm(form);
        af.onBeforeSend(function(){
            addAjaxLoader();
            return true;
        });    
        af.onSend(function(f,r){
          console.log(r);
          removeAjaxLoader();  
        });
        af.init();
    });
 });
</script>