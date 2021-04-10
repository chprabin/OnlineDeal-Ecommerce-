<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\ProductRepo;
use App\Repos\CategoryRepo;
use App\Repos\ProductCategoryRepo;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['delete','deleteAll']);
    }
    public function getRendrables(){
       return [
           'ws'=>'product_category.search-form',
           'wmcs'=>'product_category.categories-manage-search-form',
       ]; 
    }
    public function getViews(){
        return [
            'categories'=>'category.manage-list',
            'cmanage'=>'product_category.manage-list',
            'vlist'=>'product_category.view-list',
        ];
    }
    public function index(Request $req, CategoryRepo $crepo){
        $view_data['data']=$crepo->search()->getData();
        $view_data['rendrables']=$this->getRendrables();
        $viewname=$this->getViews()[$req->view];
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname='product_category.modal';
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
    }

    public function getProductCategories(Request $req, ProductRepo $prepo, CategoryRepo $crepo){
        $product=$prepo->findOrFail($req->productId);
        $view_data['product']=$product;
        $view_data['rendrables']=$this->getRendrables();
        $viewname=$this->getViews()[$req->view];
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname='product_category.modal';
        }
        if($req->wmanage){
           $categories=$crepo->search(['per_page'=>$req->per_page??10])->getData();
           $view_data['data']=$categories;
           $view_data['product_categories']=$product->categories($categories->pluck('id')->toArray());
        }elseif($req->wview){
           $view_data['data']=$product->categories()->paginate($req->per_page??10);
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
    }
    public function delete(Request $req, ProductCategoryRepo $pcrepo){
      $this->authorize('delete',ProductCategory::class); 
      $result=$pcrepo->deleteByAttributes(['productId'=>$req->productId,
       'categoryId'=>$req->categoryId]); 
       return json(['result'=>$result]);
    }

    public function deleteAll(Request $req, ProductCategoryRepo $pcrepo){
        $this->authorize('deleteAll',ProductCategory::class);
        $result=$pcrepo->deleteByAttributes(['productId'=>$req->id, 'categoryId'=>$req->categoryIds]);
        return json(['result'=>$result]);
    }
}
