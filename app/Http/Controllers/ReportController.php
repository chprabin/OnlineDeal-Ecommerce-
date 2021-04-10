<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\ProductRepo;
use App\Repos\OrderRepo;
use App\Components\Date;
use App\Components\Report;
use App\User;


class ReportController extends Controller
{
  public function __construct(){
   $this->middleware('auth')->only(['index']);   
  }
  public function index(Request $req, ProductRepo $prepo, OrderRepo $orepo, User $usermodel){
    $this->authorize('index',Report::class);
    $month=$req->month;
    if(Date::getMonthNumber($month)){
      $view_data['month']=$month;
    }else{
      $view_data['month']='january';
      $month='january';
    }
    $view_data['monthes']=Date::getMonthes();
    $view_data['orders_stats']=$orepo->getOrdersStats();
    $view_data['monthly_orders_stats']=$orepo->getOrdersStats($month);
    $view_data['monthly_orders']=json_encode($orepo->getMonthlyOrders($month));
    $view_data['users_stats']=$usermodel->getStats($month);
    $view_data['best_sellers']=$prepo->with(['firstImage'])->getBestSellers();
    if($req->ajax()){
      $view=view('report.reports-updatable')->with($view_data)->render();
      return json(['view'=>$view]);
    }
    return view('report.reports',$view_data);
  }
}
