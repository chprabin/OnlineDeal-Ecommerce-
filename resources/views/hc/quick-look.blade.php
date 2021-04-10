<div class="row">
 <div class="col lg-2 col-md-2 col-sm-6 col-xs-12">
  Name
 </div>
 <div class="col col-lg-2 col-md-2 col-sm-6 col-xs-12">
  {{$model->name}}
 </div>
</div>

<div class="row">
 <div class="col lg-2 col-md-2 col-sm-6 col-xs-12">
  Section
 </div>
 <div class="col col-lg-2 col-md-2 col-sm-6 col-xs-12">
  {{$model->section}}
 </div>
</div>

<div class="row">
 <div class="col col-12">
  Content
 </div>
 <div class="col col-12">
  <?php
    echo $model->content;
  ?>
 </div>
</div>