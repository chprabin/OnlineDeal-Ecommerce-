<form action="{{route('review_save')}}" method='POST' class='review-create-form'> 
 <input type="hidden" name='productId' value="{{$product->id}}">
 <input type="hidden" name='userId' value="{{Auth::user()->id}}">
 <div class="fg">
     <label for="" class="block">Review title</label>
     <input type="text" name='title' class='form-control control-block'>
 </div>
 <div class="fg">
  <label for="" class="block">Review rating</label>
  <select name="rating" id="" class='form-control control-block'>
      <option value="">select rating</option>
      @foreach(range(1,5) as $r)
       <option value="{{$r}}">{{$r}}</option>
      @endforeach
  </select>
 </div>
 <div class="fg">
    <label for="" class="block">Review</label>
    <textarea name="review" id="" style='min-height:100px;' class='form-control control-block' placeholder='Write your review here...'></textarea>
 </div>
 <div class="fg">
     <input type="submit" value='Create review' class='btn btn-primary'/> 
 </div>
</form>