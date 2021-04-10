<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\ProductRepo;
use App\Repos\OptionRepo;
use App\Repos\ProductOptionRepo;
use App\Models\ProductOption;

class ProductOptionController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth')->only(['delete','deleteAll']);  
    }    

    public function getViews(){
        return [
            'clist'=>'product_option.create-list',
            'elist'=>'product_option.edit-list',
            'vlist'=>'option.view-list',
        ];
    }

    public function getRendrables(){
      return [
          'wfos'=>'option.filters-options-selectables',
      ];         
    }
    public function index(Request $req){
        $view_data['rendrables']=$this->getRendrables();
        $viewname=$this->getViews()[$req->view];
        if($req->inserted_data){
         $view_data['inserted_data']=json_decode($req->inserted_data);
        }
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname='product_option.modal';
        }
        return json(['view'=>$view]);
    }

    public function getProductOptions(Request $req, ProductRepo $prepo, OptionRepo $orepo){
        $view_data['rendrables']=$this->getRendrables();
        $viewname=$this->getViews()[$req->view];
        $product=$prepo->findOrFail($req->productId);
        $view_data['product']=$product;
        $view_data['data']=$product->options()->paginate($req->per_page??10);
        if($req->inserted_data){
            $view_data['inserted_data']=json_decode($req->inserted_data);
        }
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname='product_option.modal';
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);   
    }
    public function delete(Request $req,ProductOptionRepo $porepo)
    {   
        $this->authorize('delete',ProductOption::class);
        $result=$porepo->deleteByAttributes(['productId'=>$req->productId, 'optionId'=>$req->optionId]);
        return json(['result'=>$result]);
    }
    public function deleteAll(Request $req,ProductOptionRepo $porepo)
    {   
        $this->authorize('deleteAll',ProductOption::class);
        $result=$porepo->deleteByAttributes(['productId'=>$req->productId, 'optionId'=>$req->optionIds]);
        return json(['result'=>$result]);
    }

}

