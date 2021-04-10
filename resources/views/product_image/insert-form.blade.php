<div class="cf">
 <h3>New image</h3>
 <form action="{{route('product_image_save')}}" method='POST' class='insert-form'>
  @csrf
  <div class="row">
   <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
    <a href="#" class="file-selector btn btn-block" input_name='image'>
    <i class="fa fa-upload"></i> Select image</a>
    <input type="file" name='image' class='hide'>
   </div>
   <div class="col col-lg-6 col-md-6 col-xs-12 col-sm-12">
    <input type="submit" value='upload image' class='btn btn-primary btn-block'>
   </div>
  </div>
 </form>
 <style>
  .product-image-preview img{
    width:150px;
  }
 </style>
 <div class="image-preview product-image-preview">
  <p>uploaded image will be displayed here...</p>
 </div>
</div>