<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\FilterRepo;
use App\Repos\CategoryRepo;
use App\Repos\CategoryFilterRepo;
use App\Models\CategoryFilter;

class CategoryFilterController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['deleteCategoryFilter','deleteCategoryFilters']);
    }
    public function getRendrables(){
       return [
           'ws'=>'category_filter.search-form',
           'wmsf'=>'category_filter.filters-manage-search-form',
       ]; 
    }
    public function getViews(){
        return [
            'fmanage'=>'category_filter.filters-manage',
            'fview'=>'category_filter.filters-view',
            'filters'=>'filter.manage-list',
        ];
    }
    public function index(Request $req, FilterRepo $frepo){
        $view_data['data']=$frepo->search()->getData();
        $view_data['rendrables']=$this->getRendrables();
        $viewname=$this->getViews()[$req->view];
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname='category_filter.modal';
        }   
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
    }

    public function getCategoryFilters(Request $req, FilterRepo $frepo, CategoryRepo $crepo){
        $df=$frepo->search(['per_page'=>$req->per_page??10]);
        $viewname=$this->getViews()[$req->view];
        $view_data['rendrables']=$this->getRendrables();
        $category=$crepo->findOrFail($req->categoryId);
        $view_data['category']=$category;
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname='category_filter.modal';
        }
        if($req->wmanage){
          $filters=$df->getData();
          $category_filters=$category->filters($filters->pluck('id')->toArray());
          $view_data['data']=$filters;
          $view_data['category_filters']=$category_filters;   
        }else if($req->wview){
          $view_data['data']=$category->filters()->paginate($req->per_page??10);  
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
    }

    public function deleteCategoryFilter(Request $req, CategoryFilterRepo $cfrepo){
        $result=$cfrepo->deleteByAttributes(['categoryId'=>$req->categoryId
        , 'filterId'=>$req->filterId]);
        return json(['result'=>$result]);
    }

    public function deleteCategoryFilters(Request $req,
     CategoryFilterRepo $cfrepo){
        $this->authorize('deleteCategoryFilters',CategoryFilter::class);
        $result=$cfrepo->deleteByAttributes(['categoryId'=>$req->categoryId,'filterId'=>$req->ids]);
        return json(['result'=>$result]);
    }
}

