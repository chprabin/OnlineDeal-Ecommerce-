<div class="slider-wrapper banners-slider-wrapper">
 <div class="slider">
  @foreach($data as $d)
   <div class="group">
    <div class="item">
     <div class="banner">
      <?php
       echo $d->content;
      ?>
     </div>
    </div>
   </div>
  @endforeach
 </div>
  <a href="#" class="rotator left-rotator"><span class="fa fa-angle-left"></span></a>
  <a href="#" class="rotator right-rotator"><span class="fa fa-angle-right"></span></a>
</div>