@php
 $main_image_size=isset($main_image_size)?$main_image_size.'px':'350px';
@endphp
<div class="thumbnails left">
 @foreach($product->images as $image)
  <div><img src="{{asset($image->image)}}" alt=""></div>
 @endforeach
</div>
<div class="left">
 <img src="{{asset($product->images[0]->image)}}" width='{{$main_image_size}}' heigth='{{$main_image_size}}'
 class='main-image' alt="">
</div>
<script>
 var t_images=document.querySelectorAll('.thumbnails img');
 var main_image=document.querySelector('img.main-image');
 t_images.forEach(function(img){
     img.style.cursor='pointer';
     img.onclick=function(){
         main_image.src=this.src;
     }
 });
</script>
