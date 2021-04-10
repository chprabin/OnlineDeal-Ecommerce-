<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Components\Date;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['account']);
    }
    public function account(){
        $view_data=[];
        if(auth()->user()->isAdmin()){
            $view_data['month']='january';
            $view_data['monthes']=Date::getMonthes();
        }
        return view('user.account',$view_data);
    }

    
}
