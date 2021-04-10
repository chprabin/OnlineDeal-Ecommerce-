<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use App\Repos\OptionRepo;
use Validator;
use App\Rules\NoSpecialCharacter;

class OptionController extends Controller
{
    public function __construct(){
       $this->middleware('auth')->only(['save','delete','deleteAll']); 
    }
    public function getRendrables(){
        return [
            'wi'=>'option.insert-form',
            'wfos'=>'option.filters-options-selectables',
        ];
    }
    public function getViews(){
        return [
            'clist'=>'option.create-list',
            'elist'=>'option.edit-list',
            'slist'=>'option.selectable-list',
            'vlist'=>'option.view-list',
        ];
    }
    public function index(Request $req, OptionRepo $orepo){
        $df=$orepo->search();
        $view_data['data']=$df->getData();
        $view_data['rendrables']=$this->getRendrables();
        if($req->inserted_data){
            $view_data['inserted_data']=json_decode($req->inserted_data);
        }        
        $viewname=$this->getViews()[$req->view];
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname='option.modal';
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
    }
    public function save(Request $req){
        $this->authorize('save',Option::class);
        $rules=[
          'display_text'=>['required',new NoSpecialCharacter()],
          'value'=>['required'],  
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        return json(['result'=>true, 'data'=>$data, 'msg'=>'new option is added.']);
    }

    public function delete(Request $req, OptionRepo $orepo){
        $this->authorize('delete',Option::class);
        $result=false;
        $model=$orepo->findOrFail($req->id);
        if(!$model || $orepo->delete($model)){
            $result=true;
        }
        return json(['result'=>$result]);
    }

    public function deleteAll(Request $req, OptionRepo $orepo){
        $this->authorize('deleteAll',Option::class);
    }

    public function find(Request $req, OptionRepo $orepo){
        $model=$orepo->findOrFail($req->id);
        return json(['data'=>$model]);
    }

    public function getFilterOptions(Request $req, OptionRepo $orepo){
        $view_data['data']=$orepo->per_page($req->per_page??10)->
        with(['filter'])->getByAttributes(['filterId'=>$req->filterId]);
        $view_data['rendrables']=$this->getRendrables();
        if($req->inserted_data){
            $view_data['inserted_data']=json_decode($req->inserted_data);
        }
        $viewname=$this->getViews()[$req->view];
        if($req->wm){
            $view_data['viewname']=$viewname;
            $viewname="option.modal";
        }
        $view=view($viewname)->with($view_data)->render();
        return json(['view'=>$view]);
    }
}
