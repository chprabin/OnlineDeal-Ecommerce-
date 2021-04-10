@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin no-bold">Edit category</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <div class="image-preview category-image-preview">
    <img src="{{asset($model->image)}}" alt="">
   </div>
  </div>
 </div>
 <div class="row">
  <div class="col col-lg-8 col-md-8 col-xs-12 col-sm-12">
   <form action="{{route('category_update',['id'=>$model->id])}}" method='POST' class='category-form'>
    @csrf
    @method('PUT')
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Category name</label>
      <input type="text" name='name' value="{{$model->name}}" class='form-control control-block'>
     </div>
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Category showname</label>
      <input type="text" value="{{$model->showname}}" class='form-control control-block' name='showname'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Parent category</label>
      <div class="selectable-list">
       <div class="control">
        <input type="hidden" name='parentId' class='main-input' value="{{$model->parentId}}">
        <div class="selected-item">{{$model->parentCategory?$model->parentCategory->showname:'select a category...'}}</div>
        <input type="text" class='entry invisible' placeholder='select a category...'>
        <span class="control-icon fa fa-caret-down"></span>
       </div>
       <div class="menu hide">
       </div>
      </div> 
     </div>
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <label for="" class="block">Category image</label>
      <a href="#" class="btn btn-block file-selector" input_name='image'><i class="fa fa-plus"></i> <span>Select an image</span></a>
      <input type="file" name='image' class='hide'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
     <label for="" class="block">Category filters</label>
       <a href="{{route('category_category_filters',
       ['categoryId'=>$model->id,'wm'=>1,'wcrud'=>1, 'view'=>'fmanage', 'wmsf'=>1, 'wmanage'=>1])}}"
        class="btn btn-block" onclick="event.preventDefault(); manage_filters(this.getAttribute('href'));">
        <i class="fa fa-plus"></i> add filters</a>
       <input type="hidden" name='filters'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
      <input type="submit" class='btn btn-primary btn-block' value='Save category'>
     </div>
    </div>
   </form>
  </div>
 </div> 
@endsection
@section('js')
 <script>
 var main_form=null;
 var filters_modal=null;

  createComponent('selectable-list',{selector:'.selectable-list', remote_loading:true,},function(sl){
    sl.on_init(function(list){
      var res=list.req.get('/categories?view=slist&per_page=5');
      list.update(res.view);
    });
    sl.on_data_load(function(list, url=null){
      var data={};
      if(!url){
        url='/categories';
        data['q']=list.getEntry().value;
        data['per_page']=5;
        data['view']='slist';
      }
      var res=list.req.get(url, data);
      list.update(res.view);
    });
    sl.init();
  });

  /* filters management */
  function manage_filters(url){
    if(!filters_modal){
      create_component_modal('data-list',{selector:'.category-filters'},function(modal){
        filters_modal=modal;
        manage_filters(url);
      });
    }else{
      filters_modal.on_done(function(modal){
        main_form.input('filters').setAttribute('value',JSON.stringify(modal.component.selectedIds));
      });
      filters_modal.open(url, {}, function(modal){
        modal.init();
      });
    }
  }

  createComponent('ajaxform',{selector:'.category-form'},function(form){
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
    form.onFileUploaded(function(form, input){
      create_preview_image(
        '.image-preview', input.files[0],
      );
    });
    form.init();
  });
 </script> 
@endsection