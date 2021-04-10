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
     <th>Type</th>
     <th>Code</th>
     <th>Amount</th>
     <th>Active</th>
     @if(input('wcrud'))
      <th>
       <div class="dropdown a-dropdown">
        <a href="#" class="btn dropdown-toggle"><span class="toggle-text">Bulk actions</span><i style='margin:0 3px;' class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu">
         <li>
          <form action="{{route('coupons_delete')}}" method='POST' class='delete-form'>
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
        <td>{{$d->type}}</td>
        <td>{{$d->code}}</td>
        <td>{{$d->amount}}</td>
        <td>
         @php
          $active=$d->isActive();
         @endphp
         <form action="{{route('coupon_partial_update',['id'=>$d->id])}}" method='POST' class='partial-update-form'>
          @csrf
          @method('PUT')
          <div class="toggle active-toggle sensor {{$active?'active':null}}" data-target='#active-input'><span class="ball"></span></div>
          <input type="hidden" name="active" id='active-input' value="{{$active?0:1}}">
          <input type="submit" class='hide'/>
         </form>
        </td>
        @if(input('wcrud'))
         <td>
          <a href="{{route('coupon_edit',['id'=>$d->id])}}" class='btn left' style='margin-right:3px;'>edit</a>
          <form class='delete-form' action="{{route('coupon_delete',['id'=>$d->id])}}" method='POST'>
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
 require(['ajaxform','toggle'],function(AjaxForm, Toggle){
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
   var active_toggle_elems=document.querySelectorAll('.active-toggle');
   active_toggle_elems.forEach(function(elem){
      var toggle=new Toggle();
      toggle.setElem(elem);
      toggle.init();
   });
 });
</script>