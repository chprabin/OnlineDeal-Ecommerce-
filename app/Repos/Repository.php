<?php
namespace App\Repos;
use Illuminate\Database\Eloquent\Model;
use App\Components\DataFilters\DataFilter;
class Repository{
 protected $model;
 protected $filter;
 protected $relations=[];
 protected $withCounts=[];
 protected $per_page=10;

 public function __construct(Model $model,DataFilter $filter=null){
    $this->model=$model;
    $this->filter=$filter;
 }
 public function insert(array $data){
  if(is_assoc($data)){
    return $this->model->create($data);    
  }else{
   return $this->model->insert($data);
  }
 }
 public function with(array $relations){
  $this->relations=array_merge($this->relations, $relations);
  return $this;
 }
 public function search(array $criteria=[]){
    $this->filter->setBuilder($this->model->with($this->relations)->withCount($this->withCounts));
    $this->filter->setCriteria($criteria);
    $this->filter->buildQuery(); 
    return $this->filter;
 }
 public function deleteByAttributes(array $attributes=[]){
  $query=$this->model->query();
  foreach($attributes as $key=>$value){
    if(is_array($value)){
      $query->where(function($q) use($key,$value){
        foreach($value as $v){
          $q->orWhere($key,$v);
        }
      });
    }else{
     $query->where($key,$value);
    }
  }
  return $query->delete();
 }
 public function deleteMany($list){
  return $this->model->destroy($list);
 }
 public function withCount($withCounts){
  $this->withCounts=$withCounts;
  return $this;
 }
 public function findOrFail($id){
  return $this->model->with($this->relations)->withCount($this->withCounts)->
  where($this->model->getKeyName(), $id)->firstOrFail();
 }

 public function update($model, $data){
  return $model->update($data);
 }

 public function delete($modelOrId){
  if(gettype($modelOrId)!='object'){
    return $this->model->where($this->model->getKeyName(), $modelOrId)->delete();
  }
  return $modelOrId->delete();
 }
 public function per_page($per_page){
  if(is_int($per_page) && $per_page <= $this->per_page){
    $this->per_page=$per_page;
  }
  return $this;
 }

 public function getByAttributes(array $attributes){
  $query=$this->model->with($this->relations)->withCount($this->withCounts);
  foreach($attributes as $attr=>$value){
    if(is_array($value)){
      $query->where(function($q) use($attr,$value){
        foreach($value as $v){
          $q->orWhere($attr, $v);
        }
      });
    }else{
      $query->where($attr, $value);
    }
  }
  return $query->paginate($this->per_page);
 }

 public function findByAttributes(array $attributes){
  $query=$this->model->with($this->relations)->withCount($this->withCounts);
  foreach($attributes as $attr=>$value){
    if(is_array($value)){
      $query->where(function($q) use($attr,$value){
        foreach($value as $v){
          $q->orWhere($attr, $v);
        }
      });
    }else{
      $query->where($attr, $value);
    }
  }
  return $query->first();
 }


 public function findByAttributesOrFail(array $attributes){
  $query=$this->model->with($this->relations)->withCount($this->withCounts);
  foreach($attributes as $attr=>$value){
    if(is_array($value)){
      $query->where(function($q) use($attr,$value){
        foreach($value as $v){
          $q->orWhere($attr, $v);
        }
      });
    }else{
      $query->where($attr, $value);
    }
  }
  return $query->firstOrFail();
 }

 public function all(){
   return $this->model->all();
 }


 public function getAllByAttributes(array $attributes){
  $query=$this->model->with($this->relations)->withCount($this->withCounts);
  foreach($attributes as $attr=>$value){
    if(is_array($value)){
      $query->where(function($q) use($attr,$value){
        foreach($value as $v){
          $q->orWhere($attr, $v);
        }
      });
    }else{
      $query->where($attr, $value);
    }
  }
  return $query->get();
 }

 public function getModel(){
   return $this->model;
 }

}

