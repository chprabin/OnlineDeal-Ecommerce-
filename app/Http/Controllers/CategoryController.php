<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repos\CategoryRepo;
use Validator;
use App\Rules\NoSpecialCharacter;
use App\Models\Category;
use App\Repos\ProductRepo;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
  
    public function __construct(){
        $this->middleware('auth')->only(['manage','create','save','update','edit','delete','deleteAll']);
    }
    public function getViews(){
       return [
           ''=>'category.index',
          'slist'=>'category.selectable-list',
          'mlist'=>'category.manage-list', 
       ]; 
    }
    public function getRendrables(){
        return [
            'ws'=>'category.manage-search-form',
        ];
    }
    public function index(Request $req, CategoryRepo $crepo){
        $df=$crepo->search(['per_page'=>$req->per_page??10]);
        $view_data['data']=$df->getData();
        $viewname=$this->getViews()[$req->view];
        $view_data['rendrables']=$this->getRendrables();
        if($req->wm){
         $view_data['viewname']=$viewname;   
         $viewname='category.modal';   
        }
        if($req->ajax()){
            $view=view($viewname)->with($view_data)->render();
            return json(['view'=>$view]);
        }
        return view($viewname,$view_data);
    }
    public function manage(Request $req, CategoryRepo $crepo){
        $this->authorize('manage',Category::class);
        $df=$crepo->with(['parentCategory'])->search();
        $view_data['data']=$df->getData();
        if($req->ajax()){
          $view=view('category.manage-list')->with($view_data)->render();
          return json(['view'=>$view]);      
        }
        return view('category.manage',$view_data);
    }  
    public function edit(Request $req, CategoryRepo $crepo){
        $this->authorize('edit',Category::class);
        $model=$crepo->with(['parentCategory'])->findOrFail($req->id);
        return view('category.edit',['model'=>$model]);
    }
    public function create(Request $req, CategoryRepo $crepo){
        $this->authorize('create',Category::class);
        return view('category.create');
    } 
    public function save(Request $req, CategoryRepo $crepo){
        $this->authorize('save',Category::class);
        $rules=[
            'name'=>['required',new NoSpecialCharacter(), 'max:191'],
            'showname'=>['required','unique:categories,showname', new NoSpecialCharacter()],
            'parentId'=>['nullable','exists:categories,id'],
            'image'=>['required','file','max:10000', 'mimetypes:image/jpg,image/png,image/jpeg'],
            'filters'=>[],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $model=$crepo->insert($data);
        if($model){
            return json(['result'=>true, 'msg'=>'new category is created.']);
        }
    }
    public function update(Request $req, CategoryRepo $crepo){
        $this->authorize('update',Category::class);
        $rules=[
            'name'=>['required',new NoSpecialCharacter(), 'max:191'],
            'showname'=>['required','unique:categories,showname,'.$req->id, new NoSpecialCharacter()],
            'parentId'=>['nullable','exists:categories,id'],
            'image'=>['file','max:10000', 'mimetypes:image/jpg,image/png,image/jpeg'],
            'filters'=>[],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }  
        $model=$crepo->findOrFail($req->id);
        $data=$validator->validate($rules);
        if($crepo->update($model, $data)){
            return json(['result'=>true, 'msg'=>'category has been updated.']);
        }
    }
    public function deleteAll(Request $req, CategoryRepo $crepo){
        $this->authorize('deleteAll',Category::class);
        $affected_rows=$crepo->deleteMany($req->ids);
        if($affected_rows){
            return json(['result'=>true]);
        }else{
            return json(['result'=>false]);
        }
    }

    public function delete(Request $req, CategoryRepo $crepo){
        $this->authorize('delete',Category::class);
        $result=$crepo->delete($req->id);
        return json(['result'=>$result]);
    }

    public function findByShowname(Request $req,CategoryRepo $crepo, ProductRepo $prepo){
        $category=$crepo->with(['filters'=>function($q){
            $q->with(['options']);
        }])->withCount(['products'])->findByAttributesOrFail(['showname'=>$req->showname]);
        $category_products=$prepo->getCategoryProducts($category);
        $categories=$crepo->all();
        $best_sellers=$prepo->with(['firstImage'])->getCategoryBestSellers($category);
        $top_ratings=$prepo->with(['firstImage'])->getCategoryTopRatings($category);
        $most_wished_for=$prepo->with(['firstImage'])->getCategoryMostWishedFor($category);
        $view_data=['category'=>$category, 'category_products'=>$category_products, 'categories'=>$categories,
       'best_sellers'=>$best_sellers, 'top_ratings'=>$top_ratings, 'most_wished_for'=>$most_wished_for];
       return view('category.category',$view_data);
    }

}

