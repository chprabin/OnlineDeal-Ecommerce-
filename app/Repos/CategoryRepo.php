<?php
namespace App\Repos;
use App\Repos\Repository;
use App\Models\Category;
use App\Components\DataFilters\CategoryFilter;

class CategoryRepo extends Repository{
    protected $upload_path='images/categories';
    public function __construct(Category $model, CategoryFilter $filter){
        parent::__construct($model, $filter);
    }

    public function uploadImage($file){
        return $file->store($this->upload_path);
    }
    public function insert(array $data){
        $data['image']=$this->uploadImage($data['image']);
        $model=parent::insert($data);
        if(is_array(json_decode($data['filters']))){
            $model->addFilters(json_decode($data['filters']));
        }
        return $model;
    }

    public function update($model, $data){
        if(isset($data['image'])){
            $data['image']=$this->uploadImage($data['image']);
        }
        $result=parent::update($model, $data);
        if(is_array(json_decode($data['filters']))){
            $model->addFilters(json_decode($data['filters']));
        }
        return $result;
    }

    public function getSearchedCategories($category, $categories, $query){
        $result=null;
        if($category){
          $sub_categories=$categories->filter(function($c) use($category){
            return $c->parentId==$category->id;
          });  
          $childrenIds=$this->getChildrenIds($sub_categories, $categories, []);
          $result=$this->model->whereHas('products',function($products) use($query){
            $products->where('name','like','%'.$query.'%')->orWhere('desc','like','%'.$query.'%');    
          })->whereIn('id',$childrenIds)->get();
        }else{
          $result=$this->model->whereHas('products',function($products) use($query){
            $products->where('name','like','%'.$query.'%')->orWhere('desc','like','%'.$query.'%');
          })->get();  
        }
        return $result;
    }

    public function getChildrenIds($sub_categories, $categories, $result=[]){
        foreach($sub_categories as $c){
         $c_children=$categories->filter(function($categ) use($c){
            return $categ->parentId==$c->id;
         });
         array_push($result, $c->id);
         if($c_children->count()){
          $result=$this->getChildrenIds($c_children, $categories, $result);
         }
        }
        return $result;
    }

    public function getProductLeafCategory($product)
    { 
      $categories=$product->categories;
      $categories=$categories->sortByDesc('created_at');
      return $categories->first();
    }
}