@extends('layouts.account')
@section('main')
 <div class="row">
  <div class="col col-12">
   <h1 class="no-margin">New home content</h1>
  </div>
 </div>
 <div class="row">
  <div class="col col-12">
   <form class='hc-form' action="{{route('hc_save')}}" method='POST'>
    @csrf
    <div class="row">
     <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
      <label for="" class="block">Name</label>
      <input type="text" name='name' class='form-control control-block'>
     </div>
     <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
      <label for="" class="block">Section</label>
      <select name="section" class='form-control control-block'>
       <option value="">select a section</option>
       @php
        $sections=['banner'=>'banner', 'category_grid'=>'category grid'];
       @endphp
       @foreach($sections as $key=>$text)
        <option value="{{$key}}">{{$text}}</option>
       @endforeach       
      </select>
     </div>
    </div>
    <div class="row">
     <div class="col col-12">
      <label for="" class="block">Content</label>
      <textarea id='content-input' name="content" style='min-height:200px;' class='form-control control-block'></textarea>
     </div>
    </div>
    <div class="row">
     <div class="col col-lg-4 col-md-4 col-xs-12 col-sm-12">
      <input type="submit" class='btn btn-primary btn-block' value='save home content'>
     </div>
    </div>
   </form>
  </div>
 </div>
@endsection
@section('js')
<script src="{{asset('tinymce/tinymce.min.js')}}"></script>
 <script>
  require(['ajaxform','request'],function(AjaxForm,Request){
    var req=new Request();
    var af=new AjaxForm({selector:'.hc-form'});
    af.onSend(function(f,r){
      if(r.msg){
        f.addMsg(r.result, r.msg);
      }  
      if(r.errors){
        f.addErrors(r.errors);
      }
    });
    af.init();

    tinymce.init({
     selector:'#content-input',
     plugins:'image code preview link',
     content_css:'/css/public.css,/css/grid.css',
     images_upload_handler:function(image,success,fail){
      var res=req.post('/admin/hcs/upload-image',{image:image.blob()});
      if(res.result){
        success('/'+res.location);
      }else{
        fail(JSON.stringify(result.errors));
      }
     }
    });
  });
 </script>
@endsection