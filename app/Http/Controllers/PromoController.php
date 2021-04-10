<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repos\PromoRepo;
use App\Rules\NoSpecialCharacter;
use Validator;
use App\Models\Promo;

class PromoController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['delete','manage','deleteAll','save','create']);
    }


    public function create(){
        $this->authorize('create',Promo::class);
        return view('promo.create');
    }

    public function manage(Request $req, PromoRepo $prepo){
        $this->authorize('manage',Promo::class);
        $view_data['data']=$prepo->search()->getData();
        if($req->ajax()){
            $view=view('promo.manage-list')->with($view_data)->render();
            return json(['view'=>$view]);
        }        
        return view('promo.manage',$view_data);
    }
    public function save(Request $req, PromoRepo $prepo){
        $this->authorize('save',Promo::class);
        $rules=[
            'name'=>['required','max:191',new NoSpecialCharacter(), 'unique:promos,name'],
            'type'=>['required','in:amount,percent'],
            'amount'=>['required','integer',],
            'exdate'=>['required','date'],
            'products'=>[],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $promo=$prepo->insert($data);
        if($promo){
            return json(['result'=>true, 'msg'=>'New promotion is created.']);
        }
    }

    public function delete(Request $req, PromoRepo $prepo){
        $this->authorize('delete',Promo::class);
        $result=false;
        if($prepo->delete($req->id)){
            $result=true;
        }
        return json(['result'=>$result]);
    }

    public function deleteAll(Request $req, PromoRepo $prepo){
        $this->authorize('deleteAll',Promo::class);
        $result=false;
        if($prepo->deleteMany($req->ids)){
            $result=true;
        }
        return json(['result'=>$result]);
    }
}
