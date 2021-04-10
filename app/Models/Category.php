<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=['name','showname','parentId','image'];

    public function parentCategory(){
        return $this->belongsTo('App\Models\Category','parentId');
    }
        
    public function filters(array $filterIds=[]){
        $relations=$this->belongsToMany('App\Models\Filter','category_filters', 'categoryId','filterId');
        if(count($filterIds)){
            return $relations->find($filterIds);
        }
        return $relations;   
    }

    public function addFilters(array $filterIds){
        foreach($filterIds as $filterId){
            try{
                $this->filters()->attach($filterId);
            }catch(\Exception $ex){
                continue;
            }
        }
    }

    public function products(){
        return $this->belongsToMany('App\Models\Product','product_categories','categoryId','productId');
    }
    
}
