<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repos\OrderRepo;
use App\Repos\OrderItemRepo;

class OrderItemController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['getOrderItems']);
    }
    public function getViews()
    {
        return [
            'vlist'=>'order_item.view-list',
        ];
    }
    public function getRendrables(){
        return [
            'ws'=>'order_item.search-form'
        ];
    }

    public function getOrderItems(Request $req, OrderRepo $orepo, OrderItemRepo $oirepo){
        $view_data['order']=$orepo->findOrFail($req->orderId);
        $oirepo->getModel()->where('orderId',$req->orderId);
        $view_data['data']=$oirepo->with(['product'])->search()->getData();
        $view_data['rendrables']=$this->getRendrables();
        $viewname=$this->getViews()[$req->view];
        if($req->wm){
           $view_data['viewname']=$viewname;
           $viewname='order_item.modal'; 
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
    }
}
