@extends('layouts.account')
@section('main')
<div>
</div>
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin no-bold">Create filter</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-lg-8 col-md-8 col-xs-12 col-sm-12">
   <form class='filter-form' action="{{route('filter_save')}}" method='POST'>
    @csrf
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Filter name</label>
      <input type="text" name="name" id="" class='form-control control-block'>
     </div>
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Filter display text</label>
      <input type="text" name="display_text" id="" class='form-control control-block'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Filter type</label>
      <select name="type" id="" class='form-control control-block'>
       <option value="">select a type</option>
       @foreach(['text','color'] as $type)
        <option value="{{$type}}">{{$type}}</option>
       @endforeach
      </select>
     </div>
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Filter url slug</label>
      <input type="text" name="url_slug" id="" class='form-control control-block'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Options</label>
      <a href="{{route('options',['wm'=>1, 'view'=>'clist', 'wi'=>1])}}" class="btn btn-block"
       onclick="event.preventDefault(); openOptionsModal(this.getAttribute('href'))"><i class="fa fa-plus"></i> add options</a>
       <input type="hidden" name='options'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <input type="submit" name="" id="" class='btn btn-primary btn-block' value='Save filter'>
     </div>
    </div>
   </form>
  </div>
 </div> 
@endsection
@section('js')
 <script>
  var main_form=null;
  var options_modal=null;
  /* options modal */
  function openOptionsModal(url){
    if(!options_modal){
      create_component_modal('data-list',{selector:'.options', updateUrl:'/options?view=clist'},function(modal){ 
        options_modal=modal;
        openOptionsModal(url);
      });
    }else{
     options_modal.on_done(function(modal){
       main_form.input('options').setAttribute('value',modal.component.insertedData.toJson());
     }); 
     options_modal.open(url,{inserted_data:options_modal.component.insertedData.toJson()},function(modal){
      modal.init();
     });
    }
  }
  /* end of options modal */
  createComponent('ajaxform',{selector:'.filter-form'},function(form){
    main_form=form;
    form.onSend(function(f,r){
        console.log(r)
      if(r.result){
        f.addMsg(r.result, r.msg);
      }
      if(r.errors){
        f.addErrors(r.errors);
      }
    });
    form.init();
  });
 </script> 
@endsection