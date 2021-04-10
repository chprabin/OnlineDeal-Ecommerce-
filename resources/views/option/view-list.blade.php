@if($data->count())
 <div class="row">
  @foreach($data as $d)
   <div class="col col-lg-3 col-md-3 col-xs-12 col-sm-6">
    <div class="data-item">
     <!-- row -->
     <div class="row">
      <div class="col col-12">
       {{$d->filter->name}}
      </div>
     </div>
     <!-- row -->
     <div class="row">
      @if(request()->wselect)
      <div class="col"><span class="select-box"></span></div>       
      @endif    
      <div class="col">
       @if($d->filter->type=='text')
        <span>{{$d->display_text}}</span>
       @elseif($d->filter->type=='color')
        <span style='float:left; width:15px;  height:15px; border-radius:3px;  background:{{$d->value}}'></span>
       @endif
      </div>
     </div>
    </div>
   </div>
  @endforeach
 </div>
@else
@endif
<script>
 adjustHeights('.product-options .data-item');
</script>