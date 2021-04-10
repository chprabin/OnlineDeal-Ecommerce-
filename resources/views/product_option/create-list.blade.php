@if(isset($inserted_data) && count($inserted_data))
 <h3>New options</h3>
 <div class="row">
  @foreach($inserted_data as $d)
   <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-6">
    <div class="data-item" data-id="{{$d->id}}" style='box-shadow:0 0 3px silver;'>
     <div class="row">
      <div class="col"><span class='select-box'></span></div>
      <div class="col">
       <form action="#" method='POST' class='client-delete delete-form'>
        @csrf
        @method('DELETE')
        <input type="submit" class='btn' value='Delete option'>
       </form>
      </div>
     </div>
     
     <div class="row">
       <div class="col col-6"><span>display text</span></div>
       <div class="col col-6"><span>{{$d->display_text}}</span></div>  
     </div>
    
    <div class="row">
        <div class="col col-6"><span>value</span></div>
        <div class="col col-6"><span>{{$d->value}}</span></div>
    </div>
    
    </div>
   </div>
  @endforeach
 </div>
@endif