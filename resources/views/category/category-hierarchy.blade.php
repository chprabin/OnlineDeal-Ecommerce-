@php
 function open_list($inner=false){
    if($inner){
        return "<ul class='inner'>";
    }
    return "<ul>";
 }

 function close_list(){
    return "</ul>"; 
 }

 function open_list_item($category, $active=false){
   if($active){
    return "<li><span class='normal-text active' style='text-transform:capitalize;'>"
    .$category->showname."</span>";
   }
   $url=route('category_by_showname',['showname'=>$category->showname]);
   return "<li><a href='".$url."' class='normal-text'>".$category->showname."</a>";
 }

 function close_list_item(){
     return "</li>";
 }

 function get_category_hierarchy($category, $sub_categories, $categories){
   foreach($sub_categories as $c){
    if($category->id==$c->id){
       $ret_value=open_list_item($category, true);
       $sub_categories=$categories->filter(function($categ) use($c){
           return $categ->parentId==$c->id;
       }); 
       if($sub_categories->count()){
        $ret_value.=open_list(true);
        foreach($sub_categories as $c){
          $ret_value.=open_list_item($c).close_list_item();  
        }
        $ret_value.=close_list();
       }
       $ret_value.=close_list_item();
       return $ret_value;
    }else{
       $sub_categories=$categories->filter(function($categ) use($c){
           return $c->id==$categ->parentId;
       }); 
       if($sub_categories->count()){
        $ret_value=get_category_hierarchy($category, $sub_categories, $categories);
        if($ret_value){
           $ret_value=open_list_item($c).open_list(true).$ret_value.close_list().close_list_item(); 
           return $ret_value;    
        }
       }
    }
   } 
 }

 $sub_categories=$categories->filter(function($c){
   return $c->parentId==null; 
 });
 
 $printable=open_list();
 $printable.=get_category_hierarchy($category, $sub_categories, $categories);
 $printable.=close_list();
 echo $printable;
@endphp