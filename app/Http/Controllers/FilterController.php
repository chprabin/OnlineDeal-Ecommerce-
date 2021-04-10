<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filter;
use App\Repos\FilterRepo;
use Validator;
use App\Rules\NoSpecialCharacter;

class FilterController extends Controller
{
    public function __construct(){
      $this->middleware('auth')->only(['manage','edit','create',
      'update','save','delete','deleteAll']);  
    }
    public function getRendrables(){
      return [
        'ws'=>'filter.search-form',
      ];
    }
    public function getViews(){
      return array(
        'slist'=>'filter.selectable-list',
        'mlist'=>'filter.manage-list',
      );
    }
    public function index(Request $req, FilterRepo $frepo)
    { 
      $view_data['data']=$frepo->search()->getData();
      $view_data['rendrables']=$this->getRendrables();
      $viewname=$this->getViews()[$req->view];
      if($req->wm)
      {
        $view_data['viewname']=$viewname;
        $viewname='filter.modal';
      }
      $view=view($viewname)->with($view_data)->render();
      return json(['view'=>$view]);
    }
    public function manage(Request $req, FilterRepo $frepo){
      $this->authorize('manage',Filter::class);  
      $df=$frepo->search();
      $view_data['data']=$df->getData();
      if($req->ajax()){
        $view=view('filter.manage-list')->with($view_data)->render();
        return json(['view'=>$view]);
      }  
      return view('filter.manage',$view_data);
    }
    public function edit(Request $req, FilterRepo $frepo){
      $this->authorize('edit',Filter::class);
      $view_data['model']=$frepo->findOrFail($req->id);
      return view('filter.edit',$view_data);
    }
    public function create(Request $req){
      $this->authorize('create',Filter::class);
      return view('filter.create');
    }
    public function save(Request $req, FilterRepo $frepo){
        $this->authorize('save',Filter::class);
        $rules=[
            'name'=>['required',new NoSpecialCharacter(), 'max:191','unique:filters,name'],
            'type'=>['required','in:text,color',],
            'display_text'=>['required',new NoSpecialCharacter()],
            'url_slug'=>['required','max:191',new NoSpecialCharacter(),'unique:filters,url_slug'],
            'options'=>[],
        ];
        $validator=Validator::make($req->all(), $rules);
        if($validator->fails()){
            return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
        }
        $data=$validator->validate($rules);
        $model=$frepo->insert($data);
        if($model){
            return json(['result'=>true, 'msg'=>'new filter is created.']);
        }
    }

    public function update(Request $req, FilterRepo $frepo){
      $this->authorize('update',Filter::class);
      $rules=[
          'name'=>['required',new NoSpecialCharacter(), 'max:191','unique:filters,name,'.$req->id],
          'type'=>['required','in:text,color',],
          'display_text'=>['required',new NoSpecialCharacter()],
          'url_slug'=>['required','max:191',new NoSpecialCharacter(),'unique:filters,url_slug,'.$req->id],
          'options'=>[],
      ];
      $validator=Validator::make($req->all(), $rules);
      if($validator->fails()){
          return json(['result'=>false, 'errors'=>getValidatorErrors($validator)]);
      }      
      $data=$validator->validate($rules);
      $model=$frepo->findOrFail($req->id);
      if($frepo->update($model,$data)){
        return json(['result'=>true, 'msg'=>'filter has been updated.']);
      }
    }

    public function delete(Request $req, FilterRepo $frepo){
      $this->authorize('delete',Filter::class);
      $result=$frepo->delete($req->id);
      return json(['result'=>$result]);
    }

    public function deleteAll(Request $req, FilterRepo $frepo){
      $this->authorize('deleteAll',Filter::class);
      $affected_rows=$frepo->deleteMany($req->ids);
      if($affected_rows){
        return json(['result'=>true]);
      }
      return json(['result'=>false]);
    }
}
