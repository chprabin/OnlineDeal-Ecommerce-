<h3>New option</h3>
<form action="{{route('option_save')}}" method='POST' class='insert-form'>
 @csrf
 <div class="row">
  <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
   <label for="" class="block">Display text</label>
   <input type="text"  class='form-control' name='display_text'>
  </div>
  <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
   <label for="" class='block'>Value</label>
   <input type="text" name="value" class='form-control' id="">
  </div>
 </div>
 <div class="row">
  <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
   <input type="submit" class='btn bnt-block btn-primary' value='Add option'>
  </div>
 </div>
</form>