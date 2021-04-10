@extends('layouts.account')
@section('main')
 <div class="row">
    <div class="col col-12">
     <h1 class='no-margin no-bold'>Application settings</h1>
    </div>
 </div>

 <div class="row">
  <div class="col col-12">
   <form action="{{route('settings_update')}}" method='POST' class='settings-form'>
    @csrf
    @method('PUT')
    <div class="row">
      <div class="col col-6">
       <label for="" class="left" style='margin-right:10px;'>Test mode</label>
       <span class="toggle test-mode-toggle {{$settings['test_mode']?'active':null}} sensor"
        data-target='#test-mode-input'>
        <i class="ball"></i>
       </span>
       <input type="hidden" name='test_mode' value="{{$settings['test_mode']?0:1}}"
        id='test-mode-input'>
      </div>
    </div>
     <input type="submit" class='hide'>
   </form>
  </div>
 </div>
@endsection
@section('js')
 <script>
  require(['ajaxform','toggle'],function(AjaxForm, Toggle){
    var settings_form=new AjaxForm({selector:'.settings-form'});
    settings_form.onSend(function(f,r){
        console.log(r);
    });
    settings_form.init();

    var settings_form_toggles=document.querySelectorAll('.settings-form .toggle');
    settings_form_toggles.forEach(function(te){
     var toggle=new Toggle();
     toggle.setElem(te);
     toggle.init();
    });
  });
 </script>
@endsection 