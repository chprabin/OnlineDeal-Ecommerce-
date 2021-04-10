<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repos\ProductRepo;
use App\Repos\PromoRepo;

class PromoProductController extends Controller
{
        public function index(Request $req, ProductRepo $prepo){
            $view_data['data']=$prepo->search()->getData();
            $view_data['rendrables']=$this->getRendrables();
            $viewname=$this->getViews()[$req->view];
            if($req->wm){
                $view_data['viewname']=$viewname;
                $viewname='promo_product.modal';
            }
            $view=view($viewname)->with($view_data)->render();
            return json(['view'=>$view]);
        }

        public function getViews(){
            return [
                'clist'=>'promo_product.create-list',
                'vlist'=>'promo_product.view-list',
            ];
        }
        public function getRendrables(){
            return [
                'ws'=>'promo_product.search-form',
            ];
        }

        public function getPromoProducts(Request $req, ProductRepo $prepo, PromoRepo $prrepo){
            $promo=$prrepo->findOrFail($req->promoId);
            $view_data['data']=$promo->products()->paginate($req->per_page??5);
            $view_data['rendrables']=$this->getRendrables();
            $viewname=$this->getViews()[$req->view];
            if($req->wm){
                $view_data['viewname']=$viewname;
                $viewname='promo_product.modal';
            }
            $view=view($viewname)->with($view_data)->render();
            return json(['view'=>$view]);
        }
}
