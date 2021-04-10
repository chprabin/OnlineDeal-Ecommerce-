<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\ProductRepo;
use App\Models\Product;
use App\Rules\NoSpecialCharacter;
use App\Rules\UnsignedInteger;
use App\Rules\Decimal;
use Validator;
use App\Repos\CategoryRepo;
use Illuminate\Support\Facades\DB;
use App\Repos\ReviewRepo;
use App\Components\RS;

class ProductController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->only(['create','save',
        'edit','update','delete','deleteAll','manage','getPersonalizedRecommendations']);
    }
    public function manage(Request $req,ProductRepo $prepo){
     $this->authorize('manage',Product::class);   
     $view_data['data']=$prepo->with(['brand'])->search()->getData();
     if($req->ajax()){
        $view=view('product.manage-list')->with($view_data)->render();
        return json(['view'=>$view]);
     }
     return view('product.manage',$view_data);
    }
    public function create(){
       $this->authorize('create',Product::class);
       return view('product.create'); 
    }
    public function save(Request $req, ProductRepo $prepo){
        $this->authorize('save',Product::class);
        $rules=[
            'name'=>['required','max:300',new NoSpecialCharacter()],
            'stock'=>['integer',new UnsignedInteger()],
            'brandId'=>['required','exists:brands,id'],
            'price'=>['required',new Decimal(10,2),],
            'desc'=>['required'],
            'images'=>[],
            'categories'=>[],
            'options'=>[],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $model=$prepo->insert($data);
        if($model){
            return json(['result'=>true, 'msg'=>'new product is created.']);
        }
    }

    public function edit(Request $req, ProductRepo $prepo){
        $this->authorize('edit',Product::class);
        $model=$prepo->with(['brand'])->findOrFail($req->id);
        return view('product.edit',['model'=>$model]);
    }

    public function update(Request $req, ProductRepo $prepo){
        $this->authorize('update',Product::class);
        $rules=[
            'name'=>['required','max:300',new NoSpecialCharacter()],
            'stock'=>['integer',new UnsignedInteger()],
            'brandId'=>['required','exists:brands,id'],
            'price'=>['required',new Decimal(10,2),],
            'desc'=>['required'],
            'images'=>[],
            'categories'=>[],
            'options'=>[],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $model=$prepo->findOrFail($req->id);
        $data=$validator->validate($rules);
        if($prepo->update($model, $data)){
            return json(['result'=>true, 'msg'=>'product has been updated.']);
        }
    }

    public function delete(Request $req, ProductRepo $prepo){
        $this->authorize('delete',Product::class);
        $result=$prepo->delete($req->id);
        return json(['result'=>$result]);
    }

    public function deleteAll(Request $req, ProductRepo $prepo){
        $this->authorize('deleteAll',Product::class);
        $result=false;
        if($prepo->deleteMany($req->ids)){
            $result=true;
        }
        return json(['result'=>$result]);
    }
    
    public function search(Request $req, ProductRepo $prepo, CategoryRepo $crepo){
        $categories=$crepo->all();
        $view_data['categories']=$categories;
        $category=null;
        if($req->c){
         $category=$crepo->with(['filters','parentCategory'])->
         findByAttributesOrFail(['showname'=>$req->c]);
         $view_data['category']=$category;
        }
        $searched_categories=$crepo->getSearchedCategories($category, $categories, $req->sq);
        $view_data['searched_categories']=$searched_categories;
        $products=$prepo->with(['firstImage','bestPromo','activePromos'])->withCount([
            'reviews as total_reviews',
            'reviews as total_ratings'=>function($q){
                $q->select(DB::raw('sum(rating)'));
            }
        ])->search()->getData();
        $view_data['products']=$products;
        return view('product.search',$view_data);    
    }

    public function getViews(){
     return [
         'details'=>'product.details',
         'quicklook'=>'product.quick-look',
         ''=>'product.details',
     ];   
    }
    public function details(Request $req, ProductRepo $prepo, CategoryRepo $crepo,
     ReviewRepo $rrepo, RS $rs){
        $product=$prepo->with(['bestPromo','activePromos'])->findDetailsProduct($req->id);
        $view_data['product']=$product;
        $review_groups=$rrepo->getProductReviewGroups($product);
        $view_data['review_groups']=$review_groups;
        $leaf_category=$crepo->getProductLeafCategory($product);
        $view_data['leaf_category']=$leaf_category;
        $view_data['general_recommendations']=$rs->getGeneralRecommendations($product);
        $similar_products=$prepo->getSimilarProducts($product, $leaf_category);
        $view_data['similar_products']=$similar_products;
        $viewname=$this->getViews()[$req->view];
        if($req->ajax()){
            if($req->wm){
                $view_data['viewname']=$viewname;
                $viewname='product.details-modal';
            }
          $view=view($viewname)->with($view_data)->render();
          return json(['view'=>$view]);
        }    
        return view($viewname, $view_data);
    }

    public function getProductRecommendations(Request $req,Rs $rs){
        $recommendations=$rs->getGeneralRecommendations($req->productId);
        if($recommendations->count()){
            $view=view('partials.product-details-recommendations')->with(['data'=>$recommendations])
            ->render();
            return json(['result'=>true, 'view'=>$view]);
        }
        return json(['result'=>false]);
    }
    
    public function getBestSellers(Request $req, ProductRepo $prepo){
        $from_date=$req->from_date?$req->from_date:null;
        $to_date=$req->to_date?$req->to_date:null;
        $count=$req->per_page?$req->per_page:6;
        $products=$prepo->getBestSellers($from_date, $to_date, $count);
        if($products->count()){
            $view=view('partials.home-best-sellers-slider-group')->with(['data'=>$products])->render();
            return json(['result'=>true, 'view'=>$view]);
        }
        return json(['result'=>false]);
    }
    public function getTopRatings(Request $req, ProductRepo $prepo){
        $products=$prepo->getTopRatings();
        if($products->count()){
            $view=view('partials.home-top-ratings-slider-group')->with(['data'=>$products])->render();
            return json(['result'=>true, 'view'=>$view]);
        }        
        return json(['result'=>false]);
    }

    public function getMostWished(Request $req, ProductRepo $prepo){
        $products=$prepo->getTopRatings();
        if($products->count()){
            $view=view('partials.home-most-wished-slider-group')->with(['data'=>$products])->render();
            return json(['result'=>true, 'view'=>$view]);
        }        
        return json(['result'=>false]);   
    }
    public function getPersonalizedRecommendations(Request $req, RS $rs){
        $data=$rs->getPersonalizedRecommendations($req->per_page??4);
        if($data->count()){
           $view=view('partials.personalized-recommendations-slider-group')->with(['data'=>$data])->render();
           return json(['result'=>true, 'view'=>$view]);  
        }
        return json(['result'=>false]);
    }    
}
