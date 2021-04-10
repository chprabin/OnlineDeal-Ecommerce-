<div class="row">
 @foreach($data as $d)
  <div class="col col-lg-4 col-md-4 col-sm-6 col-xs-12">
   <div class="category-grid-item cf">
     <?php
      echo $d->content;
     ?>   
   </div>
  </div>
 @endforeach
</div>
<script>
 adjustHeights('.category-grid .category-grid-item');
</script>