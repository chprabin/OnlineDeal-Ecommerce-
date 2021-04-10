@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin no-bold">Create product</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-lg-10 col-md-10 col-xs-12 col-sm-12">
   <form action="{{route('product_save')}}" method='POST' class='product-form'>
   @csrf
    <div class="row">
     <div class="col col-lg-4 col-md-4 col-sm-6 col-xs-12">
      <label for="" class="block">Name</label>
      <input type="text" name='name' class='form-control control-block'>
     </div>
     <div class="col col-lg-4 col-md-4 col-sm-6 col-xs-12">
      <label for="" class="block">Price</label>
      <input type="text" name='price' placeholder='enter in USD...' class='form-control control-block'>
     </div>
     <div class="col col-lg-4 col-md-4 col-sm-6 col-xs-12">
      <label for="" class="block">Stock</label>
      <input type="text" name='stock' class='form-control control-block'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-6">
      <label for="" class="block">Brand</label>
      <div class="selectable-list brand-selectable-list">
       <div class="control">
        <div class="selected-item"></div>
        <input type="hidden" name='brandId' class='main-input'>
        <input type="text" class='form-control control-block entry' placeholder='Select a brand'>
        <i class="fa fa-caret-down control-icon"></i>
       </div>
       <div class="menu hide"></div>
      </div>
     </div>
     <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-6">
      <label for="" class="block">Images</label>
      <a href="{{route('product_images',['wm'=>1, 'wi'=>1, 'view'=>'clist'])}}" class="btn btn-block"
      onclick="event.preventDefault(); manage_product_images(this.getAttribute('href'));"><i class="fa fa-plus"></i> Add images</a>
      <input type="hidden" name="images">
     </div>
     <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-6">
      <label for="" class="block">Categories</label>
      <a href="{{route('product_categories',['view'=>'categories','wm'=>1,'ws'=>1,'wselect'=>1])}}" class="btn btn-block"
      onclick="event.preventDefault(); manage_product_categories(this.getAttribute('href'));"><i class="fa fa-plus"></i> Add categories</a>
      <input type="hidden" name='categories'>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-6">
      <label for="" class="block">Options</label>
      <input type="hidden" name="options" id="">
      <a href="{{route('product_options',['wm'=>1, 'wfos'=>1, 'view'=>'clist'])}}" class='btn btn-block' 
      onclick="event.preventDefault(); manage_product_options(this.getAttribute('href'))"><i class="fa fa-plus"></i> Add options</a>
     </div>
    </div>
    <div class="row">
     <div class="col col-12">
      <label for="" class="block">Description</label>
      <textarea name="desc" id="desc-input" class='form-control control-block' style='min-height:300px;'></textarea>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-6">
      <input type="submit" class='btn btn-block btn-primary' value="Save product">
     </div>
    </div>
   </form>
  </div>
 </div>
@endsection
@section('js')
<script src="{{asset('tinymce/tinymce.min.js')}}"></script>
<script>
/*tiny mce */
tinymce.init({
  selector:'#desc-input',
  plugins:'code preview lists',
  content_css:'/css/public.css,/css/grid.css',
});
/* end of tinymce */
 var main_form=null;
 var images_modal=null;
 var categories_modal=null;
 var options_modal=null;
  
  createComponent('ajaxform',{selector:'.product-form'},function(form){
    main_form=form;
    form.onSend(function(f,r){
      console.log(r);
      if(r.result){
        f.addMsg(r.result, r.msg);
      }
      if(r.errors){
        f.addErrors(r.errors);
      }
    });
    form.init();
  });
  /* brand selectable list */
  createComponent('selectable-list',{selector:'.brand-selectable-list', remote_loading:true},function(list){
    list.on_init(function(list){
      var res=list.req.get('/brands',{view:'slist', per_page:'5',});
      list.update(res.view);
    });
    list.on_data_load(function(list, url=null){
      var data={};
      if(!url){
        url="/brands";
        data={view:'slist', per_page:'5', q:list.getEntry().value};
      }
      var res=list.req.get(url, data);
      list.update(res.view);
    });
    list.init();
  });

  /* product images management */
  function manage_product_images(url){
    if(!images_modal){
      create_component_modal('data-list',{selector:'.product-images',
      updateUrl:'/product-images?view=clist'},function(modal){
        images_modal=modal;
        manage_product_images(url);
      });
    }else{
      images_modal.on_done(function(modal){
        main_form.input('images').setAttribute('value',modal.component.insertedData.toJson());
      });
      images_modal.open(url, {inserted_data:images_modal.component.insertedData.toJson()}, function(modal){
        modal.init();
        modal.component.insertForm.onFileUploaded(function(form, input){
          create_preview_image('.product-image-preview',input.files[0]);
        });
      });
    } 
  }
  /* product categories management */
  function manage_product_categories(url){
    if(!categories_modal){
      create_component_modal('data-list',{selector:'.product-categories'},function(modal){
        categories_modal=modal;
        manage_product_categories(url);
      });
    }else{
      categories_modal.on_done(function(modal){
        main_form.input('categories').setAttribute('value',JSON.stringify(modal.component.selectedIds));
      });
      categories_modal.open(url,{},function(modal){
        modal.init();
      });
    }
  }

  function manage_product_options(url){
    if(!options_modal)
    {
      create_component_modal('data-list',{selector:'.product-options', 
        updateUrl:'/options?view=clist'},function(modal){
        options_modal=modal;
        manage_product_options(url);
      });
    }else{
      options_modal.on_done(function(modal){
        main_form.input('options').setAttribute('value',modal.component.insertedData.toJson());
      });
      options_modal.open(url,{inserted_data:options_modal.component.insertedData.toJson()},function(modal){
        modal.init();
        createComponent('coupled-selectables',{selector:'.filters-options-selectables'},function(cs){
          cs.list1().on_init(function(list){
            var result=list.req.get('/filters',{view:'slist', per_page:'5'});
            list.update(result.view);
          });
          cs.list1().on_data_load(function(list, url=null){
           var data={};
           if(!url){
            url='/filters';
            data={view:'slist', per_page:'5', q:list.getEntry().value};
           }
           var res=list.req.get(url, data);
           list.update(res.view);
          });
          cs.list1().on_select(function(list, filterId){
            var url='/filters/'+filterId+'/options';
            var res=list.req.get(url, {view:'slist',per_page:'5'});
            cs.list2().update(res.view);
          });
          cs.list2().on_data_load(function(list, url=null){
           var data={};
           if(!url){
            url='/options';
            data={view:'slist', per_page:'5', q:list.getEntry().value};
           }
           var res=list.req.get(url, data);
           list.update(res.view);
          });
          cs.list2().on_select(function(list, optionId){
            var url='/options/'+optionId;
            var res=list.req.get(url);
            options_modal.component.addToInsertedData(res.data);
            options_modal.component.refresh();
          });
          cs.init();
        });
      });
    }
  }
 </script> 
@endsection