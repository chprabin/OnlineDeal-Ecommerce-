<?php
 function open_blood_list(){
     return "<ul>";
 }

 function close_blood_list(){
     return "</ul>";
 }

 function open_blood_item($category, $active=false){
    if($active){
        return "<li><span class='active'>".$category->showname."</span></li>";
    }
    $url=route('category_by_showname',['showname'=>$category->shoname]);
    return "<li><a href='".$url."'>".$category->showname."</a></li>";
 }


 function close_blood_item(){
     return "</li>";
 }

 function get_catgory_blood_line($category, $sub_categories, $categories){
  foreach($sub_categories as $c){
   if($category->id==$c->id){
    return open_blood_item($c, true).close_blood_item();
   }else{
    $sub_categories=$categories->filter(function($categ) use($c){
        return $categ->parentId==$c->id; 
    });
    if($sub_categories->count()){
     $ret_value=get_catgory_blood_line($category, $sub_categories, $categories);
     if($ret_value){
        return open_blood_item($c)."<li>:</li>".close_blood_item().$ret_value;
     }
    }   
   }
  }
 }

 $base_categories=$categories->filter(function($c){
    return $c->parentId==null;
 });
 
 $printable=open_blood_list().get_catgory_blood_line($category, $base_categories, $categories).close_blood_list();
 echo $printable;
?>