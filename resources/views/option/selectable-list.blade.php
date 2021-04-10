@if($data->count())
 @foreach($data as $d)
  <div class="menu-item" data-value="{{$d->id}}" data-text="{{$d->display_text}}">
   @if($d->filter->type=='text')
    <span>{{$d->display_text}}</span>
   @elseif($d->filter->type=='color')
    <span class="left" style='width:15px; height:15px; border-radius:3px; background:{{$d->value}}'></span>
   @endif
  </div>
 @endforeach
 <div class="list-pagination">
  {{$data->links()}}
 </div>
@else
 <p> no data found</p>
@endif