@if($data->count())
 @foreach($data as $d)
  <div class="menu-item" data-value="{{$d->id}}" data-text="{{$d->name}}">{{$d->name}}</div>
 @endforeach
 <div class="list-pagination">
  {{$data->appends(reqParams(['wm']))->links()}}
 </div>
@else
 <p> no data found</p>
@endif