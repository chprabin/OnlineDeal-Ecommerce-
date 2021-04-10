@if(isset($data) && $data->count())
 <h3>Available options</h3>
 <div style='margin:10px 0;'>
  {{$data->appends(reqParams(['wi','wm']))->links()}}
 </div>
 <div class="row">
  @foreach($data as $d)
   <div class="col col-lg-3 col-md-3 col-sm-4 col-xs-6">
    <div class="data-item" data-id="{{$d->id}}">
    <!-- row -->
     <div class="row">
      <div class="col col-6">
       <span class="select-box"></span>
      </div>
      <div class="col col-6">
       @if($d->filter->type=='color')
        <span style='float:left; width:15px; height:15px; border-radius:3px; background:{{$d->value}}'></span>
       @elseif($d->filter->type=='text')
        <span>{{$d->display_text}}</span>
       @endif
      </div>
     </div>
    <!-- row -->
    @if(request()->user()->isAdmin())
     <div class="row">
      <div class="col col-6">
       <form action="{{route('option_delete',['id'=>$d->id])}}" method='POST' class="delete-form">
        @csrf
        @method('DELETE')
        <input type="submit" value="delete" id="">
       </form>
      </div>
     </div>
    @endif
    <!-- row -->
    </div>
   </div>
  @endforeach
 </div>
@endif

@if(isset($inserted_data) && count($inserted_data))
 @include('option.create-list',['$inserted_data'=>$inserted_data])
@endif