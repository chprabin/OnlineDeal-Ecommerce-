@if(isset($inserted_data) && count($inserted_data))
 <h3>New options</h3>
 <div class="row">
  @foreach($inserted_data as $d)
   <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-6">
    <div class="data-item" data-id="{{$d->id}}" style='box-shadow:0 0 3px silver;'>
     <!-- row -->
     <div class="row">
      <div class="col col-6"><span class="select-box"></span></div>
      <div class="col col-6">
       <form action="{{route('option_delete',['id'=>$d->id])}}" method='POST' class='delete-form client-delete'>
        @csrf
        @method('DELETE')
        <input type="submit" class='btn' value='delete'>
       </form>
      </div>
     </div>
     <!-- row -->
     <div class="row">
      <div class="col col-6"><span>option display text</span></div>
      <div class="col col-6"><span>{{$d->display_text}}</span></div>
     </div>
     <!-- row -->
     <div class="row">
      <div class="col col-6"><span>option value</span></div>
      <div class="col col-6">
       @if(isset($d->filter))
        @if($d->filter->type=='text')
         <span>{{$d->value}}</span>
        @elseif($d->filter->type=='color')
         <span style='float:left; width:15px; height:15px; border-radius:3px; background:{{$d->value}}'></span>
        @endif
       @else
        <span>{{$d->value}}</span>
       @endif
      </div>
     </div>
    </div>
   </div>
  @endforeach
 </div>
@endif
<script>
 adjustHeights('.data-item');
</script>