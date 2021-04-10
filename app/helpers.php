<?php

function is_assoc(array $data){
    return array_keys($data)!==range(0, count($data)-1);
}
function input($key=null){
 return !empty($key)?request()->$key:request()->all();
}

function json($value){
    return response()->json($value);
}
function getValidatorErrors($validator){
    $errors=[];
    foreach($validator->errors()->messages() as $key=>$error){
        $errors[$key]=is_array($error)?current($error):$error;
    }
    return $errors;
}

function reqParams(array $forbidden=[]){
    $params=app('request')->query();
    foreach($params as $key=>$value){
        if(in_array($key, $forbidden)){
           $params[$key]=null;
        }
    }
    return $params;
}

function shorten_text($string, $length=50){
    $string_length=strlen($string);
    if($length > $string_length){
        $length=$string_length;
    }
    $string=substr($string, 0, $length);
    $string=substr($string, 0, strrpos($string,' '))."...";
    return $string;
}

function urlHasParam($full_url, $key, $value=null){
   $query_string_params=buildQueryStringParams($full_url); 
   foreach($query_string_params as $param=>$param_value)
   {    
    if($param==$key){
       if($value==null){
        return true;
       } 
       $values=explode(';',$param_value);
       return in_array($value, $values)?true:false;
    }   
   }
}

function getBaseUrl($url){
  $query_string_start=strpos($url, '?');
  if($query_string_start!==false){
    $url=substr($url, 0, $query_string_start);
  }  
  return $url;
}

function buildUrlWithParam($full_url, $key, $value, $should_append=false){
 $base_url=getBaseUrl($full_url);
 $query_string_params=buildQueryStringParams($full_url);
 if(!$should_append || !isset($query_string_params[$key])){
   $query_string_params[$key]=$value; 
 }else{
     $values=[];
     if(isset($query_string_params[$key]))
     {
        $values=explode(';', $query_string_params[$key]); 
     }
     array_push($values, $value);
     $query_string_params[$key]=implode(';', $values);
 }
 $url=$base_url."?".buildQueryString($query_string_params);
 return $url;
}

function buildUrlRemoveParam($full_url, $key, $value=null){
 $base_url=getBaseUrl($full_url);
 $query_string_params=buildQueryStringParams($full_url);
 if(!isset($query_string_params[$key])){
    return $full_url;
 }
 $param_value=$query_string_params[$key];
 if(!$value){
   unset($query_string_params[$key]); 
 }else{
   $values=explode(';', $param_value);
   $filtered_values=array_filter($values, function($v) use($value){
    return $v!=$value;
   }); 
   if(count($filtered_values)==0){
    unset($query_string_params[$key]);
   }else{
    $query_string_params[$key]=implode(';', $filtered_values);
   }
 }
 $url=$base_url.'?'.buildQueryString($query_string_params);
 return $url;
}

function buildQueryString($params=[]){
    $qs='';
    foreach($params as $name=>$value)
    {
      $qs.=$name.'='.$value.'&';  
    }
    if(strlen($qs))
    {
     $qs=substr($qs, 0, strrpos($qs, '&'));   
    }
    return $qs;
}

function buildQueryStringParams($url=null){
    $qs='';
    if(!$url){
     $qs=app('request')->getQueryString();
    }else{
     $query_string_start=strpos($url, '?');
     if($query_string_start!==false){
       $qs=substr($url, $query_string_start+1); 
     }   
    }
    if($qs=='')
     return [];
    $qs_params=[];
    foreach(explode('&', $qs) as $qs_param){
      $param_parts=explode('=', $qs_param);
      $param_name=$param_parts[0];
      $param_value=$param_parts[1];
      $qs_params[$param_name]=$param_value;
    }
    return $qs_params;
}

function money_whole($string){
  $whole=explode('.',$string)[0];
  return number_format($whole);
}

function money_fractional($string){
  $fractional=explode('.',$string)[1];
  return $fractional;
}

function calculate_average_rating($total_ratings, $total_reviews)
{
  $avg=$total_ratings/$total_reviews;
  return get_nearest_half_or_whole($avg);
}

function get_nearest_half_or_whole($number){
  if(is_int($number))
   return $number;
  $round=round($number);
  $min=0;
  $max=0;
  if($round < $number)
  {
    $min=$round;
    $max=$min+0.5;
  }else{
    $max=$round;
    $min=$max-0.5;
  }
  $to_min=abs($min-$number);
  $to_max=abs($max-$number);
  return $to_max > $to_min?$min:$max;
}
function get_full_url($decode=true)
{ 
  $url=url()->full();
  if($decode){
    $url=urldecode($url);
  }
  return $url;
}

function create_coupon_code($prefix='cp'){
 return strtoupper($prefix.'_'.substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10)); 
}

function generate_transaction_id(){
  $out=time().uniqid(mt_rand(),true);
  $out=substr($out, 0, 20);
  return $out;
}

function buildUrlRemoveParams($full_url, $params=[]){
  $base_url=getBaseUrl($full_url);
  $query_string_params=buildQueryStringParams($full_url);
  foreach($params as $param){
    if(array_key_exists($param, $query_string_params)){
      unset($query_string_params[$param]);
    }
  }
  $url=$base_url."?".buildQueryString($query_string_params);
  return $url;    
}